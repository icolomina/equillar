<?php

namespace App\Domain\Crypt\Service;

use App\Domain\Crypt\CryptedValue;

class Encryptor
{
    public function __construct(
        #[\SensitiveParameter]
        private readonly string $appSecret
    ){}

    public function encryptMsg(string $value): CryptedValue
    {
        $nonce  = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        $cipher = sodium_crypto_secretbox($value, $nonce, $this->appSecret);

        return new CryptedValue(
            base64_encode($cipher),
            base64_encode($nonce)
        );
    }

    public function decryptMsg(string $cipherValue, string $nonce): string
    {
        return sodium_crypto_secretbox_open(base64_decode($cipherValue), base64_decode($nonce), $this->appSecret);
    }
}
