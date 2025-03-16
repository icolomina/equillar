<?php

namespace App\Message;

readonly class CheckUserInvestmentTransactionMessage
{
    public function __construct(
        public int $userInvestmentId
    ){}
}
