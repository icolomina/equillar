<?php

namespace App\Domain\User;

class User
{
    public function __construct(
        public readonly string $identifier
    ){}
}
