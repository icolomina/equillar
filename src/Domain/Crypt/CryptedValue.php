<?php

namespace App\Domain\Crypt;

readonly class CryptedValue
{
    public function __construct(
        public string $cipher,
        public string $nonce
    ){}
}
