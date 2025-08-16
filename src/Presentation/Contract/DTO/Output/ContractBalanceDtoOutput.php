<?php

namespace App\Presentation\Contract\DTO\Output;

readonly class ContractBalanceDtoOutput
{
    public function __construct(
        
        public float $available,
        public float $reserveFund,
        public float $commision,
        public float $fundsReceived,
        public float $payments,
        public float $projectWithdrawals,
        public float $reserveFundContributions,
        public float  $percentajeFundsReceived
    ) {}
}
