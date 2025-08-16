<?php

namespace App\Message;

readonly class CheckUserContractMessage
{
    public function __construct(
        public int $userInvestmentId
    ){}
}
