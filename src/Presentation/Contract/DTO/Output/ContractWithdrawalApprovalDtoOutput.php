<?php

namespace App\Presentation\Contract\DTO\Output;

readonly class ContractWithdrawalApprovalDtoOutput
{
    public function __construct(
        public ContractWithdrawalRequestDtoOutput $contractWithdrawalRequestDtoOutput,
        public ?string $approvedAt,
        public string $status
    ){}
}
