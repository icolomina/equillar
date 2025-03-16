<?php

namespace App\Presentation\Contract\DTO\Input;

class CreateUserContractDtoInput {

    public function __construct(
        public readonly string $contractAddress,
        public readonly string $hash,
        public readonly string $deposited,
        public readonly string $status,
        public readonly string $fromAddress
    ){}
}