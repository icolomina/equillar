# How Equillar Implements AEAD Encryption

I would like to share in this post an important change we've implemented in Equillar: **migrating from SecretBox to AEAD (Authenticated Encryption with Associated Data)** to protect sensitive data such as the private keys of the system wallets.

## Why the Change?

Originally, Equillar used **sodium_crypto_secretbox** to encrypt sensitive data. It's a solid and proven algorithm, but after reading [Stellar's security guide for web-based projects](https://developers.stellar.org/docs/build/security-docs/securing-web-based-projects), we realized we could significantly improve our security by implementing **AEAD**.

Stellar's recommendation is clear: when you encrypt data that has associated context (such as a wallet's address, creation timestamp, etc.), AEAD is the right choice because it **protects both the data and its context**.

## SecretBox vs AEAD: What's the Difference?

Before diving into code, let's understand what we gain with this change:

### SecretBox (what we had)
```php
// Only encrypts the message
$cipher = sodium_crypto_secretbox($message, $nonce, $key);
```

**Features:**
- ✅ Encryption + authentication of the message
- ✅ Simple and fast
- ❌ Doesn't protect associated metadata
- ❌ Doesn't bind ciphertext to its context

**Ideal use case:** Encrypting a file without relevant metadata.

### AEAD (what we implemented)
```php
// Encrypts the message AND authenticates additional data
$cipher = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
    $message,
    $additionalData,  // ← The key difference
    $nonce,
    $key
);
```

**Features:**
- ✅ Encryption + authentication of the message
- ✅ Authentication of Additional Data (AD) without encrypting it
- ✅ Ciphertext is bound to its context
- ✅ Impossible to "move" encrypted data to another context

**Ideal use case:** Encrypting a private key by binding it to the specific wallet that owns it.

### Why Does This Matter?

Imagine an attacker compromises your database and obtains:
- An encrypted private key from Wallet A
- Data from Wallet B

**With SecretBox:** The attacker could try to use Wallet A's ciphertext in Wallet B's context.

**With AEAD:** This is impossible. Wallet A's ciphertext is cryptographically bound to Wallet A's data (address, timestamp, blockchain). If you try to decrypt it with Wallet B's data, authentication automatically fails.

## The Architecture: Schema Builders and Tagged Services

One of the challenges when implementing AEAD is generating **Additional Data** consistently and maintainably. Our solution: **Schema Builders**.

### The Concept

Each entity that needs encryption has its own "builder" that constructs the Additional Data:

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

**Why do we version schemas?** Because Additional Data must be **exactly the same** during encryption and decryption. If in the future we need to change what data we include, we create `SystemWalletV2SchemaBuilder` and maintain compatibility with old data.

### Tagged Services: Symfony Auto-Registration

To manage multiple schema builders in a scalable way, we use [Symfony Tagged Services](https://symfony.com/doc/current/service_container/tags.html). The interface has the `#[AutoconfigureTag]` attribute:

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

This means that **every class implementing the interface is automatically registered** with the `app.crypt.aead_schema` tag. 

### Loading Schema Builders with a Configurator

To inject all tagged schema builders into the `EntitySchemaBuilderLocator`, we use a [Symfony Service Configurator](https://symfony.com/doc/current/service_container/configurators.html):

```php
<?php

namespace App\Domain\Crypt\Aead\Service;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class EntitySchemaBuilderLocatorConfigurator 
{
    public function __construct(
        #[AutowireIterator('app.crypt.aead_schema')]
        private readonly iterable $handlers
    ) {
    }

    public function configure(EntitySchemaBuilderLocator $locator): void
    {
        $collection = new EntitySchemaBuilderCollection();
        
        foreach($this->handlers as $schemaBuilder) {
            $collection->addSchemaBuilder($schemaBuilder);
        }

        $locator->setSchemaBuilders($collection);
    }
}
```

The configurator uses `#[AutowireIterator]` to automatically receive all services tagged with `app.crypt.aead_schema`, then builds the collection and injects it into the locator.

The `EntitySchemaBuilderCollection` organizes schema builders by entity class and version:

```php
<?php

namespace App\Domain\Crypt\Aead\Service;

class EntitySchemaBuilderCollection 
{
    private array $schemaBuilders = [];

    public function addSchemaBuilder(EntitySchemaBuilderInterface $schema): void
    {
        // Organize by entity class
        if (!isset($this->schemaBuilders[$schema->getEntityClass()])) {
            $this->schemaBuilders[$schema->getEntityClass()] = [];
        }

        // Store by version
        $this->schemaBuilders[$schema->getEntityClass()][$schema->getVersion()] = $schema;
    }

    public function getLatestSchemaVersion(string $entity): ?EntitySchemaBuilderInterface 
    {
        if(!isset($this->schemaBuilders[$entity])) {
            return null;
        }

        $versions = $this->schemaBuilders[$entity];

        // Sort versions descending (v2, v1, etc.)
        uksort($versions, fn($a, $b) => version_compare($b, $a));

        return reset($versions);
    }

    public function getSchemaBuilder(string $entity, string $version): ?EntitySchemaBuilderInterface
    {
        return $this->schemaBuilders[$entity][$version] ?? null;
    }
}
```

This structure allows us to:
- Store multiple versions of schemas for the same entity
- Retrieve the latest version automatically with `getLatestSchemaVersion()`
- Access specific versions for decrypting old data with `getSchemaBuilder(entity, version)`

**Example:** If you have `SystemWalletV1SchemaBuilder` and `SystemWalletV2SchemaBuilder`, both are stored under `SystemWallet::class` with keys `'v1'` and `'v2'`. When encrypting new data, we use `v2`. When decrypting old data, we use the version stored in the ciphertext metadata.

We register the configurator in `config/services.yaml`:

```yaml
App\Domain\Crypt\Aead\Service\EntitySchemaBuilderLocator:
    configurator: ['@App\Domain\Crypt\Aead\Service\EntitySchemaBuilderLocatorConfigurator', 'configure']
```

Now the `EntitySchemaBuilderLocator` has access to all schema builders:

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

**Advantage:** When you add a new schema builder, you simply implement the interface and it's already available. The configurator automatically collects and injects all tagged services, no manual configuration needed.

## AeadEncryptor: The Heart of the System

Now comes the interesting part: how we encrypt using AEAD with **key derivation**.

### Why Derive Keys?

Instead of directly using the master key for each encryption, we derive a unique subkey based on the Additional Data. This adds an extra layer of security: even if two entities have the same data, each encryption uses a different subkey (thanks to the random context).

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
        // 1. Generate random nonce
        $nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        
        // 2. Derive subkey ID from Additional Data hash
        $hash = sodium_crypto_generichash($additionalData, '', SODIUM_CRYPTO_GENERICHASH_BYTES_MIN);
        ['id' => $subkeyId] = unpack('Jid', $hash);
        $subkeyId = $subkeyId & 0x7FFFFFFFFFFFFFFF; // Force positive
        
        // 3. Generate random context for KDF
        $context = random_bytes(SODIUM_CRYPTO_KDF_CONTEXTBYTES);
        
        // 4. Derive the subkey
        $derivedKey = $this->deriveKeyFromSubkeyId($subkeyId, $context);
        
        // 5. Encrypt with AEAD
        $cipher = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
            $value,
            $additionalData,
            $nonce,
            $derivedKey
        );

        sodium_memzero($derivedKey); // Clear memory

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

### Encryption Flow Step by Step:

1. **Random nonce** (24 bytes): Ensures each encryption is unique
2. **SubkeyId derived from AD**: BLAKE2b hash of Additional Data → 64-bit integer
3. **Random context** (8 bytes): Input for KDF, stored with ciphertext
4. **Key derivation**: `sodium_crypto_kdf_derive_from_key(subkeyId, context, masterKey)`
5. **AEAD encryption**: XChaCha20-Poly1305-IETF with authenticated AD
6. **Memory cleanup**: `sodium_memzero()` to erase the derived subkey

### Decryption: The Reverse Path

```php
public function decryptMsg(AeadCryptedValue $aeadCryptedValue, string $additionalData): string 
{
    $cipher = base64_decode($aeadCryptedValue->ciphertext, true);
    $nonce  = base64_decode($aeadCryptedValue->nonce, true);
    $context = base64_decode($aeadCryptedValue->context, true);

    // Derive the same subkey using stored subkeyId and context
    $derivedKey = $this->deriveKeyFromSubkeyId(
        $aeadCryptedValue->subkeyId, 
        $context
    );

    // Decrypt while verifying Additional Data
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

**Key Points:**
- The `subkeyId` and `context` are stored with the ciphertext
- The Additional Data must be **exactly the same** as during encryption
- If AD, nonce, context, or ciphertext are modified, authentication fails

## EntityAeadEncryptor: The Application Layer

Finally, we need a service that brings everything together: schema builders + AEAD encryptor. This is `EntityAeadEncryptor`:

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
        // 1. Get the appropriate schema builder
        $schemaBuilder = $this->schemaBuilderLocator
            ->getLatestSchemaBuilder($entity::class);
        
        if ($schemaBuilder === null) {
            throw new \RuntimeException(
                'No schema builder found for entity: ' . $entity::class
            );
        }
        
        // 2. Build Additional Data
        $associatedData = $schemaBuilder->build($entity);

        // 3. Encrypt
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
        // Support both AeadCryptedValue and array (from DB)
        $cryptedValue = ($cryptedValue instanceof AeadCryptedValue) 
            ? $cryptedValue
            : $this->serializer->denormalize($cryptedValue, AeadCryptedValue::class);

        // 1. Get the schema builder by version
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

        // 2. Rebuild the same Additional Data
        $associatedData = $schemaBuilder->build($entity);
        
        // 3. Decrypt
        return $this->aeadEncryptor->decryptMsg($cryptedValue, $associatedData);
    }
}
```

### Usage in Practice

This is how we encrypt a SystemWallet's private key:

```php
// Create wallet
$systemWallet = new SystemWallet();
$systemWallet->setAddress($stellarAddress);
$systemWallet->setBlockchainNetwork($blockchainNetwork);
$systemWallet->setCreatedAt(new \DateTimeImmutable());

// Persist first (we need complete fields for AD)
$entityManager->persist($systemWallet);
$entityManager->flush();

// Encrypt the private key bound to the wallet
$cryptedValue = $entityAeadEncryptor->encryptEntity(
    $systemWallet, 
    $secretSeed
);

// Save the encrypted result
$systemWallet->setPrivateKey($this->serializer->normalize($cryptedValue));
$entityManager->flush();
```

And to decrypt:

```php
// Retrieve wallet from DB
$systemWallet = $systemWalletRepository->find($id);

// Decrypt (automatically validates that AD matches)
$secretSeed = $entityAeadEncryptor->decryptEntity(
    $systemWallet,
    $systemWallet->getPrivateKey()
);

// Use the private key
$keyPair = KeyPair::fromSeed($secretSeed);
```

## Advantages of This Architecture

✅ **Enhanced security**: Encrypted data is bound to its context  
✅ **Versioning**: We can evolve schemas without breaking old data  
✅ **Scalable**: Adding encryption to new entities is trivial  
✅ **Separation of Concerns**: Schema builders, encryption, and application layers are decoupled  
✅ **Testable**: Each layer can be tested independently  
✅ **Tagged Services**: Auto-registration of schema builders without manual configuration  

## Testing: Validating Security

Of course, we've created comprehensive tests to validate everything works correctly:

```php
public function testDecryptionFailsWithDifferentEntity(): void
{
    $systemWallet1 = EntityGenerator::systemWallet();
    $systemWallet2 = EntityGenerator::systemWallet();
    $plaintext = 'secret_data';

    // Encrypt with first wallet
    $encrypted = $this->entityAeadEncryptor->encryptEntity(
        $systemWallet1, 
        $plaintext
    );

    // Try to decrypt with second wallet (different AD)
    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage('Decryption or authentication failed');

    $this->entityAeadEncryptor->decryptEntity($systemWallet2, $encrypted);
}
```

This test confirms that **you cannot decrypt data from one wallet using another wallet's context**, even if you have access to the ciphertext.

## Conclusion

Migrating from SecretBox to AEAD was a decision motivated by following the security best practices recommended by Stellar. The result is a more robust system that:

- Protects sensitive data by cryptographically binding it to its context
- Is maintainable and scalable thanks to schema builders
- Allows evolution without breaking compatibility through versioning
- Uses key derivation to add an extra layer of security

If you're developing on Stellar and handling sensitive data, I strongly recommend reading the [official security guide](https://developers.stellar.org/docs/build/security-docs/securing-web-based-projects) and considering AEAD for your project.

---

*The complete code is available in the [Equillar repository](https://github.com/icolomina/equillar) under the AGPL-3.0 license.*
