<?php

namespace App\Blockchain\Stellar\Exception\Transaction;

interface TransactionExceptionInterface
{
    public function getStatus(): string;
    public function isSimulationFailure(): bool;
    public function getError(): string;
    public function getFailureLedger(): int;
    public function getHash(): ?string;
}
