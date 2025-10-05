<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
