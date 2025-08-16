<?php

namespace App\Domain\User\Portfolio;

readonly class PerTokenValue 
{
    public function __construct(
        public string $token,
        public string $value
    ){}
}
