<?php

namespace App\Blockchain\Stellar\Exception\Transaction;

use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class GetTransactionException extends \RuntimeException implements TransactionExceptionInterface
{
    private GetTransactionResponse $getTransactionResponse;

    public function __construct(GetTransactionResponse $getTransactionResponse)
    {
        $this->getTransactionResponse = $getTransactionResponse;
        $message = sprintf('Soroban Sent transaction failed: ' . $this->getError());
        parent::__construct($message);
    }

    public function getGetTransactionResponse(): GetTransactionResponse
    {
        return $this->getTransactionResponse;
    }

    public function getError(): string
    {
        return match($this->getTransactionResponse->status) {
            GetTransactionResponse::STATUS_NOT_FOUND => 'Transaction Not Found',
            GetTransactionResponse::STATUS_FAILED => $this->getTransactionResponse->getError()->message,
            default => 'Unknown error'
        };
    }

    public function getStatus(): string
    {
        return $this->getTransactionResponse->getStatus();
    }

    public function isSimulationFailure(): bool
    {
        return false;
    }

    public function getFailureLedger(): int
    {
        return $this->getTransactionResponse->getLedger() ?? $this->getTransactionResponse->getLatestLedger();
    }

    public function getHash(): ?string
    {
        return $this->getTransactionResponse->getTxHash();
    }
}
