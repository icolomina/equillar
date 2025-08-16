<?php

namespace App\Presentation\Token\DTO\Output;

class TokenDtoOutput 
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $code,
        public readonly string $address,
        public readonly string $createdAt,
        public readonly bool $enabled,
        public readonly string $issuer,
        public readonly int $decimals,
        public readonly ?string $locale = null,
        public readonly ?string $fiatReference = null
    ){}
}