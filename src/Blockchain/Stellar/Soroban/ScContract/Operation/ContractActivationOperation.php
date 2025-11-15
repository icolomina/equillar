<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\ContractActivationOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Contract\Contract;
use App\Persistence\ContractCode\ContractCodeStorageInterface;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class ContractActivationOperation
{
    public function __construct(
        private readonly ContractActivationOperationBuilder $contractActivationOperationBuilder,
        private readonly ContractCodeStorageInterface $contractCodeStorage,
        private readonly ProcessTransactionService $processTransactionService,
    ) {
    }

    public function activateContract(Contract $contract): GetTransactionResponse
    {
        $lastDeployedContractCode = $this->contractCodeStorage->getLastdeployedContractCode();
        $operation = $this->contractActivationOperationBuilder->build($contract, $lastDeployedContractCode->getWasmId());

        $transactionResponse = $this->processTransactionService->sendTransaction($operation, true);

        return $transactionResponse;
    }
}
