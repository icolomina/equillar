<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
*/
namespace App\Blockchain\Stellar\Exception\Transaction;

class ContractCallFunctionResultException extends \RuntimeException implements TransactionExceptionInterface
{
    private ?string $hash;

    public function __construct(string $errorCode, string $errorType, ?string $trxHash)
    {
        $error = $errorCode.' - '.$errorType;
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
