<?php

namespace App\Blockchain\Stellar\Exception\Transaction;

use Soneso\StellarSDK\Soroban\Responses\SimulateTransactionResponse;

class SimulatedTransactionException extends \RuntimeException implements TransactionExceptionInterface
{
    private SimulateTransactionResponse $simulateTransactionResponse;

    public function __construct(SimulateTransactionResponse $simulateTransactionResponse)
    {
        $this->simulateTransactionResponse = $simulateTransactionResponse;
        $message = sprintf('Soroban Simulated transaction failed: ' . $simulateTransactionResponse->resultError ?? $simulateTransactionResponse->getError());
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
        return $this->simulateTransactionResponse->resultError ?? $this->simulateTransactionResponse->getError()?->message ?? 'Unknown Error';
    }

    public function getFailureLedger(): int
    {
        return $this->simulateTransactionResponse->getLatestLedger() ?? strtotime('now');
    }

    public function getHash(): ?string
    {
        return null;
    }
}
