<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\GetContractBalanceOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class GetContractBalanceOperation
{
    public function __construct(
        private readonly GetContractBalanceOperationBuilder $getContractBalanceOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService,
    ) {
    }

    public function getContractBalance(Contract $contract): GetTransactionResponse
    {
        $invokeContractHostFunction = $this->getContractBalanceOperationBuilder->build($contract);

        return $this->processTransactionService->sendTransaction($invokeContractHostFunction, true);
    }
}
