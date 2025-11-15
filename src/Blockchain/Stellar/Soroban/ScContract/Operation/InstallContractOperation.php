<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\InstallContractOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;

class InstallContractOperation
{
    public function __construct(
        private InstallContractOperationBuilder $installContractOperationBuilder,
        private ProcessTransactionService $processTransactionService,
    ) {
    }

    public function install(string $wasmId, ?array $constructorArgs = null): string
    {
        $operation = $this->installContractOperationBuilder->build($wasmId, $constructorArgs);
        $transactionResponse = $this->processTransactionService->sendTransaction($operation, true);

        return $transactionResponse->getCreatedContractId();
    }
}
