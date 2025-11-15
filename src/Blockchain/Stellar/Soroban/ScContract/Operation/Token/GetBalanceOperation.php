<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Token;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Token\Builder\GetBalanceOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Token;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class GetBalanceOperation
{
    public function __construct(
        private readonly GetBalanceOperationBuilder $getBalanceOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService,
    ) {
    }

    public function getTokenBalance(Token $token, string $addressToCheck): GetTransactionResponse
    {
        $invokeContractHostFunction = $this->getBalanceOperationBuilder->build($token, $addressToCheck);

        return $this->processTransactionService->sendTransaction($invokeContractHostFunction, true);
    }
}
