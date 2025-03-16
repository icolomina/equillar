<?php

namespace App\Presentation\Contract\DTO\Input;

readonly class InitializeContractDtoInput
{
    public function __construct(
        public readonly string $projectAddress,
        public readonly int $returnType,
        public readonly int $returnMonths,
        public readonly int $minPerInvestment
    ){}
}
