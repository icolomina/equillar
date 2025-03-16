<?php

namespace App\Domain;

class Identifier
{
    public function __construct(
        public readonly mixed $identifier
    ){}
}
