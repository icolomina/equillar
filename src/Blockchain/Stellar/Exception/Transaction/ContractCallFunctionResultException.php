<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
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

    public function getCreatedAt(): ?string
    {
        return null;
    }
}
