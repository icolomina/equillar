<?php

namespace App\Blockchain\Stellar\Exception\Transaction;

use Soneso\StellarSDK\Soroban\Responses\SendTransactionResponse;

class SendTransactionException extends \RuntimeException implements TransactionExceptionInterface
{
    private SendTransactionResponse $sendTransactionResponse;

    public function __construct(SendTransactionResponse $sendTransactionResponse)
    {
        $this->sendTransactionResponse = $sendTransactionResponse;
        $message = sprintf('Soroban Sent transaction failed: ' . $this->getError());
        parent::__construct($message);
    }

    public function getSendTransactionResponse(): SendTransactionResponse
    {
        return $this->sendTransactionResponse;
    }

    public function getError(): string
    {
        return match($this->sendTransactionResponse->status) {
            SendTransactionResponse::STATUS_DUPLICATE => 'Transaction Duplicated',
            SendTransactionResponse::STATUS_TRY_AGAIN_LATER => 'No Ledger available. Try again later',
            SendTransactionResponse::STATUS_ERROR => $this->sendTransactionResponse->getError()->message,
            default => 'Unknown error'
        };
    }

    public function getStatus(): string
    {
        return $this->sendTransactionResponse->getStatus();
    }

    public function isSimulationFailure(): bool
    {
        return false;
    }

    public function getFailureLedger(): int
    {
        return $this->sendTransactionResponse->getLatestLedger() ?? strtotime('now');
    }

    public function getHash(): ?string
    {
        return $this->sendTransactionResponse->getHash();
    }
}
