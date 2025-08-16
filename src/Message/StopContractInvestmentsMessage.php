<?php

namespace App\Message;

readonly class StopContractInvestmentsMessage
{
    public function __construct(
        public int $contractId,
        public ?string $reason = null

    ){}
}
