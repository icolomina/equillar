# Cómo Equillar implementa encriptación AEAD siguiendo las mejores prácticas de Stellar

Hoy quiero compartir con vosotros un cambio importante que hemos implementado en Equillar: **la migración de SecretBox a AEAD (Authenticated Encryption with Associated Data)** para proteger datos sensibles como las claves privadas de nuestras wallets del sistema.

## ¿Por qué el cambio?

Originalmente, Equillar usaba **sodium_crypto_secretbox** para encriptar datos sensibles. Es un algoritmo sólido y probado, pero después de leer la [guía de seguridad de Stellar para proyectos web](https://developers.stellar.org/docs/build/security-docs/securing-web-based-projects), nos dimos cuenta de que podíamos mejorar significativamente nuestra seguridad implementando **AEAD**.

La recomendación de Stellar es clara: cuando encriptas datos que tienen contexto asociado (como la dirección de una wallet, el timestamp de creación, etc.), AEAD es la opción correcta porque **protege tanto los datos como su contexto**.

## SecretBox vs AEAD: ¿Cuál es la diferencia?

Antes de entrar en código, vamos a entender qué ganamos con este cambio:

### SecretBox (lo que teníamos)
```php
// Solo encripta el mensaje
$cipher = sodium_crypto_secretbox($mensaje, $nonce, $clave);
```

**Características:**
- ✅ Encriptación + autenticación del mensaje
- ✅ Simple y rápido
- ❌ No protege metadatos asociados
- ❌ No vincula el ciphertext con su contexto

**Caso de uso ideal:** Encriptar un archivo sin metadatos relevantes.

### AEAD (lo que implementamos)
```php
// Encripta el mensaje Y autentica los datos adicionales
$cipher = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
    $mensaje,
    $datosAdicionales,  // ← La diferencia clave
    $nonce,
    $clave
);
```

**Características:**
- ✅ Encriptación + autenticación del mensaje
- ✅ Autenticación de Additional Data (AD) sin encriptarlo
- ✅ El ciphertext está vinculado a su contexto
- ✅ Imposible "trasladar" datos encriptados a otro contexto

**Caso de uso ideal:** Encriptar una clave privada vinculándola a la wallet específica que la posee.

### ¿Por qué esto importa?

Imagina que un atacante compromete tu base de datos y obtiene:
- Una clave privada encriptada de la Wallet A
- Los datos de la Wallet B

**Con SecretBox:** El atacante podría intentar usar el ciphertext de la Wallet A en el contexto de la Wallet B.

**Con AEAD:** Esto es imposible. El ciphertext de la Wallet A está criptográficamente vinculado a los datos de la Wallet A (dirección, timestamp, blockchain). Si intentas desencriptarlo con datos de la Wallet B, la autenticación falla automáticamente.

## La arquitectura: Schema Builders y Tagged Services

Uno de los desafíos al implementar AEAD es generar los **Additional Data** de forma consistente y mantenible. Nuestra solución: **Schema Builders**.

### El concepto

Cada entidad que necesita encriptación tiene su propio "builder" que construye los Additional Data:

```php
<?php

namespace App\Domain\Crypt\Aead\Service\Schema;

use App\Domain\Crypt\Aead\EntitySchemaBuilderInterface;
use App\Entity\SystemWallet;

class SystemWalletV1SchemaBuilder implements EntitySchemaBuilderInterface
{
    public function build(object $systemWallet): string
    {
        $adData = [
            'address'    => $systemWallet->getAddress(),
            'blockchain' => $systemWallet->getBlockchainNetwork()->getLabel(),
            'timestamp'  => $systemWallet->getCreatedAt()->getTimestamp(),
        ];

        ksort($adData);
        return json_encode($adData);
    }

    public function getEntityClass(): string
    {
        return SystemWallet::class;
    }

    public function getVersion(): string
    {
        return 'v1';
    }
}
```

**¿Por qué versionamos los schemas?** Porque los Additional Data deben ser **exactamente iguales** en encriptación y desencriptación. Si en el futuro necesitamos cambiar qué datos incluimos, creamos `SystemWalletV2SchemaBuilder` y mantenemos la compatibilidad con datos antiguos.

### Tagged Services: autoregistro mágico

Para gestionar múltiples schema builders de forma escalable, usamos **Symfony Tagged Services**. La interfaz tiene el atributo `#[AutoconfigureTag]`:

```php
<?php

namespace App\Domain\Crypt\Aead;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.crypt.aead_schema')]
interface EntitySchemaBuilderInterface
{
    public function build(object $entity): string;
    public function getEntityClass(): string;
    public function getVersion(): string;
}
```

Esto significa que **cada clase que implemente la interfaz se registra automáticamente** con el tag `app.crypt.aead_schema`. Luego, el `EntitySchemaBuilderLocator` los recolecta:

```php
<?php

namespace App\Domain\Crypt\Aead\Service;

use App\Domain\Crypt\Aead\EntitySchemaBuilderInterface;

class EntitySchemaBuilderLocator
{
    public function __construct(
        private readonly EntitySchemaBuilderCollection $schemaBuilders
    ) {
    }

    public function getLatestSchemaBuilder(string $entityClass): ?EntitySchemaBuilderInterface
    {
        return $this->schemaBuilders->getLatestSchemaVersion($entityClass);
    }

    public function getSchemaBuilder(string $schema, string $version): ?EntitySchemaBuilderInterface
    {
        return $this->schemaBuilders->getSchemaBuilder($schema, $version);
    }
}
```

**Ventaja:** Cuando añades un nuevo schema builder, simplemente implementas la interfaz y ya está disponible. No hay que modificar configuración ni registros manuales.

## AeadEncryptor: el corazón del sistema

Ahora viene la parte interesante: cómo encriptamos usando AEAD con **key derivation**.

### ¿Por qué derivar claves?

En lugar de usar directamente la clave maestra para cada encriptación, derivamos una subclave única basada en los Additional Data. Esto añade una capa extra de seguridad: incluso si dos entidades tienen los mismos datos, cada encriptación usa una subclave diferente (gracias al context aleatorio).

```php
<?php

namespace App\Domain\Crypt\Aead\Service;

class AeadEncryptor
{
    public function encryptMsg(
        string $value, 
        string $additionalData, 
        string $schema, 
        string $version
    ): AeadCryptedValue {
        // 1. Generar nonce aleatorio
        $nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        
        // 2. Derivar subkey ID desde el hash del Additional Data
        $hash = sodium_crypto_generichash($additionalData, '', SODIUM_CRYPTO_GENERICHASH_BYTES_MIN);
        ['id' => $subkeyId] = unpack('Jid', $hash);
        $subkeyId = $subkeyId & 0x7FFFFFFFFFFFFFFF; // Forzar positivo
        
        // 3. Generar context aleatorio para KDF
        $context = random_bytes(SODIUM_CRYPTO_KDF_CONTEXTBYTES);
        
        // 4. Derivar la subclave
        $derivedKey = $this->deriveKeyFromSubkeyId($subkeyId, $context);
        
        // 5. Encriptar con AEAD
        $cipher = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
            $value,
            $additionalData,
            $nonce,
            $derivedKey
        );

        sodium_memzero($derivedKey); // Limpiar memoria

        return new AeadCryptedValue(
            base64_encode($cipher),
            base64_encode($nonce),
            $schema,
            $version,
            CryptEngine::AEAD->value,
            $this->key->id,
            base64_encode($context),
            $subkeyId
        );
    }

    private function deriveKeyFromSubkeyId(int $subkeyId, string $context): string
    {
        return sodium_crypto_kdf_derive_from_key(
            SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES,
            $subkeyId,
            $context,
            $this->encryptionKey
        );
    }
}
```

### Flujo de encriptación paso a paso:

1. **Nonce aleatorio** (24 bytes): Garantiza que cada encriptación sea única
2. **SubkeyId derivado del AD**: Hash BLAKE2b del Additional Data → entero de 64 bits
3. **Context aleatorio** (8 bytes): Input para KDF, almacenado con el ciphertext
4. **Derivación de clave**: `sodium_crypto_kdf_derive_from_key(subkeyId, context, masterKey)`
5. **Encriptación AEAD**: XChaCha20-Poly1305-IETF con AD autenticado
6. **Limpieza de memoria**: `sodium_memzero()` para borrar la subclave derivada

### Desencriptación: el camino inverso

```php
public function decryptMsg(AeadCryptedValue $aeadCryptedValue, string $additionalData): string 
{
    $cipher = base64_decode($aeadCryptedValue->ciphertext, true);
    $nonce  = base64_decode($aeadCryptedValue->nonce, true);
    $context = base64_decode($aeadCryptedValue->context, true);

    // Derivar la misma subclave usando subkeyId y context almacenados
    $derivedKey = $this->deriveKeyFromSubkeyId(
        $aeadCryptedValue->subkeyId, 
        $context
    );

    // Desencriptar verificando el Additional Data
    $plain = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
        $cipher,
        $additionalData,
        $nonce,
        $derivedKey
    );

    sodium_memzero($derivedKey);

    if ($plain === false) {
        throw new \RuntimeException(
            'Decryption or authentication failed. Data may have been tampered with.'
        );
    }

    return $plain;
}
```

**Puntos clave:**
- El `subkeyId` y `context` se almacenan con el ciphertext
- El Additional Data debe ser **exactamente el mismo** que en la encriptación
- Si AD, nonce, context o ciphertext se modifican, la autenticación falla

## EntityAeadEncryptor: la capa de aplicación

Finalmente, necesitamos un servicio que una todo: schema builders + AEAD encryptor. Este es `EntityAeadEncryptor`:

```php
<?php

namespace App\Domain\Crypt\Aead\Service;

use App\Domain\Crypt\Aead\AeadCryptedValue;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EntityAeadEncryptor
{
    public function __construct(
        private readonly EntitySchemaBuilderLocator $schemaBuilderLocator,
        private readonly AeadEncryptor $aeadEncryptor,
        private readonly DenormalizerInterface $serializer
    ) {
    }

    public function encryptEntity(object $entity, string $plain): AeadCryptedValue
    {
        // 1. Obtener el schema builder adecuado
        $schemaBuilder = $this->schemaBuilderLocator
            ->getLatestSchemaBuilder($entity::class);
        
        if ($schemaBuilder === null) {
            throw new \RuntimeException(
                'No schema builder found for entity: ' . $entity::class
            );
        }
        
        // 2. Construir Additional Data
        $associatedData = $schemaBuilder->build($entity);

        // 3. Encriptar
        return $this->aeadEncryptor->encryptMsg(
            $plain, 
            $associatedData, 
            $schemaBuilder->getEntityClass(),
            $schemaBuilder->getVersion()
        );
    }

    public function decryptEntity(
        object $entity, 
        array|AeadCryptedValue $cryptedValue
    ): string {
        // Soportar tanto AeadCryptedValue como array (desde BD)
        $cryptedValue = ($cryptedValue instanceof AeadCryptedValue) 
            ? $cryptedValue
            : $this->serializer->denormalize($cryptedValue, AeadCryptedValue::class);

        // 1. Obtener el schema builder por versión
        $schemaBuilder = $this->schemaBuilderLocator->getSchemaBuilder(
            $cryptedValue->schema, 
            $cryptedValue->version
        );
        
        if ($schemaBuilder === null) {
            throw new \RuntimeException(
                'No schema builder found for entity: ' . 
                $cryptedValue->schema . ' version: ' . $cryptedValue->version
            );
        }

        // 2. Reconstruir el mismo Additional Data
        $associatedData = $schemaBuilder->build($entity);
        
        // 3. Desencriptar
        return $this->aeadEncryptor->decryptMsg($cryptedValue, $associatedData);
    }
}
```

### Uso en la práctica

Así es como encriptamos la clave privada de un SystemWallet:

```php
// Crear wallet
$systemWallet = new SystemWallet();
$systemWallet->setAddress($stellarAddress);
$systemWallet->setBlockchainNetwork($blockchainNetwork);
$systemWallet->setCreatedAt(new \DateTimeImmutable());

// Persistir primero (necesitamos campos completos para AD)
$entityManager->persist($systemWallet);
$entityManager->flush();

// Encriptar la clave privada vinculada al wallet
$cryptedValue = $entityAeadEncryptor->encryptEntity(
    $systemWallet, 
    $secretSeed
);

// Guardar el resultado encriptado
$systemWallet->setPrivateKey([
    'ciphertext' => $cryptedValue->ciphertext,
    'nonce' => $cryptedValue->nonce,
    'schema' => $cryptedValue->schema,
    'version' => $cryptedValue->version,
    'engine' => $cryptedValue->engine,
    'keyId' => $cryptedValue->keyId,
    'context' => $cryptedValue->context,
    'subkeyId' => $cryptedValue->subkeyId,
]);

$entityManager->flush();
```

Y para desencriptar:

```php
// Recuperar wallet de BD
$systemWallet = $systemWalletRepository->find($id);

// Desencriptar (valida automáticamente que el AD coincide)
$secretSeed = $entityAeadEncryptor->decryptEntity(
    $systemWallet,
    $systemWallet->getPrivateKey()
);

// Usar la clave privada
$keyPair = KeyPair::fromSeed($secretSeed);
```

## Ventajas de esta arquitectura

✅ **Seguridad mejorada**: Los datos encriptados están vinculados a su contexto  
✅ **Versionado**: Podemos evolucionar los schemas sin romper datos antiguos  
✅ **Escalable**: Añadir encriptación a nuevas entidades es trivial  
✅ **Separation of Concerns**: Schema builders, encriptación y aplicación están desacoplados  
✅ **Testeable**: Cada capa se puede testear independientemente  
✅ **Tagged Services**: Auto-registro de schema builders sin configuración manual  

## Testing: validando la seguridad

Por supuesto, hemos creado tests exhaustivos para validar que todo funciona correctamente:

```php
public function testDecryptionFailsWithDifferentEntity(): void
{
    $systemWallet1 = EntityGenerator::systemWallet();
    $systemWallet2 = EntityGenerator::systemWallet();
    $plaintext = 'secret_data';

    // Encriptar con primera wallet
    $encrypted = $this->entityAeadEncryptor->encryptEntity(
        $systemWallet1, 
        $plaintext
    );

    // Intentar desencriptar con segunda wallet (diferente AD)
    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage('Decryption or authentication failed');

    $this->entityAeadEncryptor->decryptEntity($systemWallet2, $encrypted);
}
```

Este test confirma que **no puedes desencriptar datos de una wallet usando el contexto de otra wallet**, incluso si tienes acceso al ciphertext.

## Conclusión

Migrar de SecretBox a AEAD fue una decisión motivada por seguir las mejores prácticas de seguridad recomendadas por Stellar. El resultado es un sistema más robusto que:

- Protege datos sensibles vinculándolos criptográficamente a su contexto
- Es mantenible y escalable gracias a los schema builders
- Permite evolución sin romper compatibilidad con el versionado
- Usa key derivation para añadir una capa extra de seguridad

Si estás desarrollando sobre Stellar y manejas datos sensibles, te recomiendo encarecidamente leer la [guía de seguridad oficial](https://developers.stellar.org/docs/build/security-docs/securing-web-based-projects) y considerar AEAD para tu proyecto.

¿Preguntas? ¿Sugerencias? ¡Déjame un comentario! 🚀

---

*El código completo está disponible en el [repositorio de Equillar](https://github.com/icolomina/equillar) bajo licencia AGPL-3.0.*
