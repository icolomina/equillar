<?php

namespace App\Message;

readonly class ApproveRequestWithdrawalMessage
{
    public function __construct(
        public int $requestWithdrawalId
    ){}

    
}
