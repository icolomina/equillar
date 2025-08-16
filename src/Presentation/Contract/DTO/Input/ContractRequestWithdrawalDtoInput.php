<?php

namespace App\Presentation\Contract\DTO\Input;

readonly class ContractRequestWithdrawalDtoInput
{
    public function __construct(
        public float|int $requestedAmount
    ){}
}
