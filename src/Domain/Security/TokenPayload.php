<?php

namespace App\Domain\Security;

readonly class TokenPayload
{
    public function __construct(
        public string $iss,
        public string $aud,
        public string $iat,
        public string $nbf,
        public string $exp,
        public string $uuid
    ){}
}
