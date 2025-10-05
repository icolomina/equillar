<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\PayUserContractOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Contract\UserContract;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class PayUserContractOperation
{
    public function __construct(
        private readonly PayUserContractOperationBuilder $claimOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService,
    ) {
    }

    public function payUserContract(UserContract $userContract): GetTransactionResponse
    {
        $operation = $this->claimOperationBuilder->build($userContract);

        return $this->processTransactionService->sendTransaction($operation, true);
    }

    public function processPayUserContractTransaction(string $hash): GetTransactionResponse
    {
        return $this->processTransactionService->waitForTransaction($hash);
    }
}
