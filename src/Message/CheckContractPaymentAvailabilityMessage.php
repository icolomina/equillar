<?php

namespace App\Message;

class CheckContractPaymentAvailabilityMessage
{
    public function __construct(
        public int $contractPaymentAvailabilityId,
    ){}
}