<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\CheckContractPaymentAvailabilityOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Contract\ContractPaymentAvailability;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class CheckContractPaymentAvailabilityOperation
{
    public function __construct(
        private readonly CheckContractPaymentAvailabilityOperationBuilder $checkContractPaymentAvailabilityOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService,
    ) {
    }

    public function checkContractPaymentAvailability(ContractPaymentAvailability $contractPaymentAvailability): GetTransactionResponse
    {
        $operation = $this->checkContractPaymentAvailabilityOperationBuilder->build($contractPaymentAvailability);

        return $this->processTransactionService->sendTransaction($operation, true);
    }
}
