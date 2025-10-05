<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
