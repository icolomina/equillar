<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\DeployWasmOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;

class DeployContractOperation
{
    public function __construct(
        private DeployWasmOperationBuilder $deployWasmOperationBuilder,
        private ProcessTransactionService $processTransactionService
    ){}

    public function deploy(string $wasmCode): string
    {
        $operation = $this->deployWasmOperationBuilder->build($wasmCode);
        $transactionResponse = $this->processTransactionService->sendTransaction($operation);
        return $transactionResponse->getWasmId();
    }
}
