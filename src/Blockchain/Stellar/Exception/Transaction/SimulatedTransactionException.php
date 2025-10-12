<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
*/
namespace App\Blockchain\Stellar\Exception\Transaction;

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
        return match (true) {
            $this->simulateTransactionResponse->resultError => $this->simulateTransactionResponse->resultError,
            $this->simulateTransactionResponse->getError() && $this->simulateTransactionResponse->getError()->message => $this->simulateTransactionResponse->getError()->message,
            default => 'Unknown error',
        };
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
}
