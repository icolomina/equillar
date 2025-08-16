<?php

namespace App\Domain\Blockchain\Stellar;

class TransactionData
{
    public function __construct(
        public bool $isSuccessful,
        public int $ledger,
        public string $feeCharged,
        public string $hash
    ){}
}
