<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Blockchain\Stellar\Exception\Transaction;

use App\Domain\Contract\ContractError;
use Soneso\StellarSDK\Soroban\Responses\SimulateTransactionResponse;

class SimulatedTransactionException extends \RuntimeException implements TransactionExceptionInterface
{
    private SimulateTransactionResponse $simulateTransactionResponse;

    public function __construct(SimulateTransactionResponse $simulateTransactionResponse)
    {
        $this->simulateTransactionResponse = $simulateTransactionResponse;
        $message = sprintf('Soroban Simulated transaction failed: %s', $this->getError());
        parent::__construct($message);
    }

    public function getSimulatedTransactionResponse(): SimulateTransactionResponse
    {
        return $this->simulateTransactionResponse;
    }

    public function getStatus(): string
    {
        return 'SIMULATION_FAILED';
    }

    public function isSimulationFailure(): bool
    {
        return true;
    }

    public function getError(): string
    {
        $contractError = $this->getContractError();
        return $contractError?->getMessage() ?? 'Unknown error';
    }

    public function getFailureLedger(): int
    {
        return $this->simulateTransactionResponse->getLatestLedger();
    }

    public function getHash(): ?string
    {
        return null;
    }

    public function getCreatedAt(): ?string
    {
        return null;
    }

    private function getContractError(): ?ContractError
    {
        $rawError = match (true) {
            !empty($this->simulateTransactionResponse->resultError) => $this->simulateTransactionResponse->resultError,
            $this->hasErrorMessage() => $this->simulateTransactionResponse->getError()->message,
            $this->hasErrorData() => json_encode($this->simulateTransactionResponse->getError()->data),
            default => null,
        };

        return (is_null($rawError)) ? null : ContractError::fromRawError($rawError);
    }

    private function hasErrorMessage(): bool
    {
        return $this->simulateTransactionResponse->getError() && $this->simulateTransactionResponse->getError()->message;
    }

    private function hasErrorData(): bool
    {
        return $this->simulateTransactionResponse->getError() && $this->simulateTransactionResponse->getError()->data;
    }
}
