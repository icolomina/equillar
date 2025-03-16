<?php

namespace App\Presentation\Contract\DTO\Output;

readonly class ContractInvestmentBalanceDtoOutput
{
    public function __construct(
        public float $available,
        public float $reserveFund,
        public ?float $commision
    ) {}
}
