<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\Crypt\Service;

use App\Domain\Crypt\CryptedValue;
use Symfony\Component\DependencyInjection\Attribute\Lazy;

#[Lazy]
class Encryptor
{
    private string $key;

    public function __construct(
        #[\SensitiveParameter]
        private readonly string $cryptKey,
    ) {
        $key = hex2bin($this->cryptKey);
        if ($key === false || strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
            throw new \InvalidArgumentException('appSecret must be 64 hex chars (32 bytes)');
        }
        
        $this->key = $key;
    }

    public function encryptMsg(string $value): CryptedValue
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = sodium_crypto_secretbox($value, $nonce, $this->key);

        return new CryptedValue(
            base64_encode($cipher),
            base64_encode($nonce)
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

        $plain = sodium_crypto_secretbox_open($cipher, $nonce, $this->key);
        if ($plain === false) {
            throw new \RuntimeException('Decryption failed');
        }

        return $plain;
    }
}
