<?php

namespace App\Presentation\Contract\DTO\Output;

readonly class ContractWithdrawalRequestDtoOutput
{
    public function __construct(
        public int $id,
        public string $contractLabel,
        public string $requestedAt,
        public string $requestedBy,
        public string $requestedAmount,
        public ?string $status,
        public ?string $approvedAt = null
    ){}
}
