<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\Contract\Exception;

use App\Entity\ContractTransaction;

/**
 * Exception thrown when a contract execution fails due to business logic or validation errors.
 * 
 * This indicates the transaction was processed but rejected by the smart contract.
 * Examples: insufficient funds, invalid contract state, validation failures, etc.
 * 
 * HTTP Status Code: 422 Unprocessable Entity
 */
final class ContractExecutionFailedException extends \RuntimeException
{
    public function __construct(
        string $message,
        private readonly int|string $contractId,
        private readonly ?string $transactionHash,
    ) {
        parent::__construct($message, 422);
    }

    public function getContractId(): int|string
    {
        return $this->contractId;
    }

    public function getTransactionHash(): ?string
    {
        return $this->transactionHash;
    }

    public static function fromContractTransaction(ContractTransaction $contractTransaction): self
    {
        return new self(
            message: sprintf(
                'Contract execution failed during %s operation: %s',
                $contractTransaction->getFunctionCalled(),
                $contractTransaction->getError()
            ),
            contractId: $contractTransaction->getContractAddress(),
            transactionHash: $contractTransaction->getTrxHash()
        );
    }
}
