<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\ContractWithdrawalOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class ContractWithdrawalOperation
{
    public function __construct(
        private readonly ContractWithdrawalOperationBuilder $contractWithdrawalOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService,
    ) {
    }

    public function projectWithdrawn(Contract $contract, float $amount): GetTransactionResponse
    {
        $operation = $this->contractWithdrawalOperationBuilder->build($contract, $amount);

        return $this->processTransactionService->sendTransaction($operation, true);
    }
}
