<?php

namespace App\Presentation\Contract\DTO\Output;

readonly class ContractInvestmentWithdrawalRequestDtoOutput
{
    public function __construct(
        public ContractDtoOutput $contract,
        public string $requestedAt,
        public float|int $requestedAmount,
        public ?string $status,
        public ?string $hash
    ){}
}
