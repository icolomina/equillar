<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
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
