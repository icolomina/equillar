<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Blockchain\Stellar\Exception\Transaction;

use Soneso\StellarSDK\Soroban\Responses\SendTransactionResponse;

class SendTransactionException extends \RuntimeException implements TransactionExceptionInterface
{
    private SendTransactionResponse $sendTransactionResponse;

    public function __construct(SendTransactionResponse $sendTransactionResponse)
    {
        $this->sendTransactionResponse = $sendTransactionResponse;
        $message = sprintf('Soroban Sent transaction failed: '.$this->getError());
        parent::__construct($message);
    }

    public function getSendTransactionResponse(): SendTransactionResponse
    {
        return $this->sendTransactionResponse;
    }

    public function getError(): string
    {
        return match ($this->sendTransactionResponse->status) {
            SendTransactionResponse::STATUS_DUPLICATE => 'Transaction Duplicated',
            SendTransactionResponse::STATUS_TRY_AGAIN_LATER => 'No Ledger available. Try again later',
            SendTransactionResponse::STATUS_ERROR => $this->sendTransactionResponse->getError()->message,
            default => 'Unknown error',
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

    public function getCreatedAt(): ?string
    {
        return ($this->sendTransactionResponse->getLatestLedgerCloseTime() > 0)
            ? date($this->sendTransactionResponse->getLatestLedgerCloseTime())
            : null
        ;
    }
}
