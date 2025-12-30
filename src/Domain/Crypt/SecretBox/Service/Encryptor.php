<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Crypt\SecretBox\Service;

use App\Domain\Crypt\CryptEngine;
use App\Domain\Crypt\CryptKey;
use App\Domain\Crypt\SecretBox\SecretBoxCryptedValue;
use App\Domain\Crypt\Service\Vault;
use Symfony\Component\DependencyInjection\Attribute\Lazy;

#[Lazy]
class Encryptor
{
    private CryptKey $key;
    private string $encriptionKey;

    public function __construct(
        #[\SensitiveParameter]
        private readonly Vault $vault,
    ) {
        $this->key = $this->vault->getSbKey();
        $eKey = hex2bin($this->key->value);
        if ($eKey === false || strlen($eKey) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
            throw new \InvalidArgumentException('appSecret must be 64 hex chars (32 bytes)');
        }
        
        $this->encriptionKey = $eKey;
    }

    public function encryptMsg(string $value): SecretBoxCryptedValue
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = sodium_crypto_secretbox($value, $nonce, $this->encriptionKey);

        return new SecretBoxCryptedValue(
            base64_encode($cipher),
            base64_encode($nonce),
            CryptEngine::SECRET_BOX->value,
        );
    }

    public function decryptMsg(string $cipherValue, string $nonce): string
    {
        $cipher = base64_decode($cipherValue, true);
        $nonce  = base64_decode($nonce, true);

        if ($cipher === false || $nonce === false) {
            throw new \InvalidArgumentException('Invalid base64 input');
        }
        if (strlen($nonce) !== SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
            throw new \InvalidArgumentException('Invalid nonce length');
        }

        $plain = sodium_crypto_secretbox_open($cipher, $nonce, $this->encriptionKey);
        if ($plain === false) {
            throw new \RuntimeException('Decryption failed');
        }

        return $plain;
    }
}
