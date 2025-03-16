<?php

namespace App\Message;

readonly class CheckContractInvestmentWithdrawalRequestMessage
{
    public function __construct(
        public int $requestWithdrawalId
    ){}
}
