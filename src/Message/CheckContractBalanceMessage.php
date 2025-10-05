<?php

namespace App\Message;

readonly class CheckContractBalanceMessage
{
    public function __construct(
        public string|int $contractId,
        public ?int $startLedger = null,
    ) {
    }
}
