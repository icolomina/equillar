<?php

namespace App\Presentation\Contract\DTO\Input;

readonly class ContractInvestmentRequestWithdrawalDtoInput
{
    public function __construct(
        public string $hash,
        public float|int $requestedAmount
    ){}
}
