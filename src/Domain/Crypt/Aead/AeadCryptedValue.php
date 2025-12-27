<?php

namespace App\Domain\Crypt\Aead;

final readonly class AeadCryptedValue
{
    public function __construct(
        public string $ciphertext,
        public string $nonce,
        public string $schema,
        public string $version,
        public string $engine,
        public string $keyId,
        public string $context,
        public int $subkeyId,
    ) {
    }
}