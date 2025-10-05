<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\DeployWasmOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;

class DeployContractOperation
{
    public function __construct(
        private DeployWasmOperationBuilder $deployWasmOperationBuilder,
        private ProcessTransactionService $processTransactionService,
    ) {
    }

    public function deploy(string $wasmCode): string
    {
        $operation = $this->deployWasmOperationBuilder->build($wasmCode);
        $transactionResponse = $this->processTransactionService->sendTransaction($operation);

        return $transactionResponse->getWasmId();
    }
}
