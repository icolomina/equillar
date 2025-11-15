<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
