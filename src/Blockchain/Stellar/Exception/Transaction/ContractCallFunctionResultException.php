<?php

namespace App\Blockchain\Stellar\Exception\Transaction;

use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;

class ContractCallFunctionResultException extends \RuntimeException implements TransactionExceptionInterface
{

    private ?string $hash;

    public function __construct(string $errorCode, string $errorType, ?string $trxHash)
    {
        $error = $errorCode . ' - ' . $errorType;
        $this->hash = $trxHash;
        parent::__construct($error);
    }

    public function getStatus(): string
    {
        return 'CONTRACT_FUNCTION_ERROR_RESULT';
    }
    
    public function isSimulationFailure(): bool
    {
        return false;
    }
    
    public function getError(): string
    {
        return $this->message;
    }
    
    public function getFailureLedger(): int
    {
        return strtotime('now');
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }
}
