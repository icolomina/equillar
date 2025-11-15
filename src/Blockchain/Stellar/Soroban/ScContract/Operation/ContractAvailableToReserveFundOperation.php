<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
