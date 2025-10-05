<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\StopOrRestartInvestmentsOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class ContractStopOrRestartInvestmentsOperation
{
    public function __construct(
        private readonly StopOrRestartInvestmentsOperationBuilder $stopOrRestartInvestmentsOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService,
    ) {
    }

    public function stopOrRestartInventments(Contract $contract, string $type): GetTransactionResponse
    {
        $invokeContractHostFunction = $this->stopOrRestartInvestmentsOperationBuilder->build($contract, $type);

        return $this->processTransactionService->sendTransaction($invokeContractHostFunction, true);
    }
}
