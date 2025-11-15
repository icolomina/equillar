<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
