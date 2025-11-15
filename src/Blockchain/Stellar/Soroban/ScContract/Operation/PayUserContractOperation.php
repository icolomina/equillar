<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
