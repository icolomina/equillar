<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Crypt\Aead\Service;

use App\Domain\Crypt\Aead\AeadCryptedValue;
use App\Domain\Crypt\CryptEngine;
use App\Domain\Crypt\CryptKey;
use App\Domain\Crypt\Service\Vault;
use Symfony\Component\DependencyInjection\Attribute\Lazy;

#[Lazy]
class AeadEncryptor
{
    private CryptKey $key;
    private string $encryptionKey;

    public function __construct(
        #[\SensitiveParameter]
        private readonly Vault $vault,
    ) {
        $this->key = $this->vault->getAeadKey();
        $eKey = hex2bin($this->key->value);
        if ($eKey === false || strlen($eKey) !== SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES) {
            throw new \InvalidArgumentException('Key must be 64 hex chars (32 bytes) for XChaCha20-Poly1305-IETF');
        }
        
        $this->encryptionKey = $eKey;
    }

    public function encryptMsg(string $value, string $additionalData, string $schema, string $version): AeadCryptedValue
    {
        $nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        
        $hash = sodium_crypto_generichash($additionalData, '', SODIUM_CRYPTO_GENERICHASH_BYTES_MIN);
        ['id' => $subkeyId] = unpack('Jid', $hash);
        $subkeyId = $subkeyId & 0x7FFFFFFFFFFFFFFF;
        $context = random_bytes(SODIUM_CRYPTO_KDF_CONTEXTBYTES);
        
        $derivedKey = $this->deriveKeyFromSubkeyId($subkeyId, $context);
        
        $cipher = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
            $value,
            $additionalData,
            $nonce,
            $derivedKey
        );

        sodium_memzero($derivedKey);

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

    public function decryptMsg(AeadCryptedValue $aeadCryptedValue, string $additionalData): string {

        $cipher = base64_decode($aeadCryptedValue->ciphertext, true);
        $nonce  = base64_decode($aeadCryptedValue->nonce, true);

        if ($cipher === false || $nonce === false) {
            throw new \InvalidArgumentException('Invalid base64 input');
        }
        if (strlen($nonce) !== SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES) {
            throw new \InvalidArgumentException('Invalid nonce length');
        }

        $context = base64_decode($aeadCryptedValue->context, true);
        if ($context === false) {
            throw new \InvalidArgumentException('Invalid base64 input');
        }

        $derivedKey = $this->deriveKeyFromSubkeyId($aeadCryptedValue->subkeyId, $context);

        $plain = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
            $cipher,
            $additionalData,
            $nonce,
            $derivedKey
        );

        sodium_memzero($derivedKey);

        if ($plain === false) {
            throw new \RuntimeException('Decryption or authentication failed. Data may have been tampered with.');
        }

        return $plain;
    }

    /**
     * Gets the current key ID being used for encryption
     *
     * @return string The key identifier
     */
    public function getCurrentKeyId(): string
    {
        return $this->key->id;
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
