<?php

namespace App\Domain\UserContract;

class UserContractPaymentCalendarItem
{
    public function __construct(
        public readonly string $date,
        public readonly float $value,
        public bool $isTransferred = false,
        public ?string $transferredAt = null,
        public ?string $willBeTransferredAt = null
    ){}
}
