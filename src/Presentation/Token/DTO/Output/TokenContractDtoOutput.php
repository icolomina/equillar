<?php

namespace App\Presentation\Token\DTO\Output;

readonly class TokenContractDtoOutput
{
    public function __construct(
        public string $name,
        public string $code,
        public string $issuer,
        public int $decimals,
        public ?string $locale = null,
        public ?string $fiatReference = null
    ){}
}
