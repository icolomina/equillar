<?php

namespace App\Blockchain\Stellar\Exception\Transaction;

use Soneso\StellarSDK\Soroban\Responses\SimulateTransactionResponse;

class SimulatedTransactionException extends \RuntimeException implements TransactionExceptionInterface
{
    private SimulateTransactionResponse $simulateTransactionResponse;

    public function __construct(SimulateTransactionResponse $simulateTransactionResponse)
    {
        $this->simulateTransactionResponse = $simulateTransactionResponse;
        $message = sprintf('Soroban Simulated transaction failed: %s' , $this->getError());
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
        return match(true) {
            $this->simulateTransactionResponse->resultError => $this->simulateTransactionResponse->resultError,
            $this->simulateTransactionResponse->getError() && $this->simulateTransactionResponse->getError()->message => $this->simulateTransactionResponse->getError()->message,
            default => 'Unknown error'
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
}
