<?php

namespace App\Presentation\Contract\DTO\Output;

class ContractDtoOutput 
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $address,
        public readonly string $token,
        public readonly int $tokenDecimals,
        public readonly string $tokenCode,
        public readonly float $rate,
        public readonly string $createdAt,
        public readonly ?string $initializedAt,
        public readonly bool $initialized,
        public readonly string $issuer,
        public readonly int $claimMonths,
        public readonly string $label,
        public readonly bool $fundsReached,
        public readonly ?string $description,
        public readonly ?string $shortDescription,
        public ?ContractInvestmentBalanceDtoOutput $contractBalance,
        public readonly string $status,
        public readonly string $goal,
        public readonly bool $retrieveContractBalanceError = false
    ){}
}