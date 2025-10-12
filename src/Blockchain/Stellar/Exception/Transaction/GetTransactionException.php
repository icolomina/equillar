<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
*/
namespace App\Blockchain\Stellar\Exception\Transaction;

use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class GetTransactionException extends \RuntimeException implements TransactionExceptionInterface
{
    private GetTransactionResponse $getTransactionResponse;

    public function __construct(GetTransactionResponse $getTransactionResponse)
    {
        $this->getTransactionResponse = $getTransactionResponse;
        $message = sprintf('Soroban Sent transaction failed: '.$this->getError());
        parent::__construct($message);
    }

    public function getGetTransactionResponse(): GetTransactionResponse
    {
        return $this->getTransactionResponse;
    }

    public function getError(): string
    {
        return match ($this->getTransactionResponse->status) {
            GetTransactionResponse::STATUS_NOT_FOUND => 'Transaction Not Found',
            GetTransactionResponse::STATUS_FAILED => $this->getTransactionResponse->getError()->message,
            default => 'Unknown error',
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
    
    public function getCreatedAt(): ?string
    {
        return $this->getTransactionResponse->getCreatedAt();
    }
}
