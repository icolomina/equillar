<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\ContractAvailableToReserveFundOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Contract\ContractBalanceMovement;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class ContractAvailableToReserveFundOperation
{
    public function __construct(
        private readonly ContractAvailableToReserveFundOperationBuilder $contractAvailableToReserveFundOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService,
    ) {
    }

    public function moveAvailableFundsToReserve(ContractBalanceMovement $contractBalanceMovement): GetTransactionResponse
    {
        $operation = $this->contractAvailableToReserveFundOperationBuilder->build($contractBalanceMovement);
        return $this->processTransactionService->sendTransaction($operation, true);
    }
}
