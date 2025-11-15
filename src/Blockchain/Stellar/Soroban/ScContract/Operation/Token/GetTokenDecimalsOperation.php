<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Token;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Token\Builder\GetTokenDecimalsBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Token;

class GetTokenDecimalsOperation
{
    public function __construct(
        private readonly GetTokenDecimalsBuilder $getTokenDecimalsBuilder,
        private readonly ProcessTransactionService $processTransactionService,
        private readonly ScContractResultBuilder $scContractResultBuilder,
    ) {
    }

    public function getTokenDecimals(Token $token): mixed
    {
        $invokeContractHostFunction = $this->getTokenDecimalsBuilder->build($token);
        $transactionResponse = $this->processTransactionService->sendTransaction($invokeContractHostFunction);
        $resultValue = $transactionResponse->getResultValue();

        if ($resultValue->getError()) {
            throw new \RuntimeException('Unable to check balance for address: '.$resultValue->getError()->getCode()->getValue());
        }

        return $this->scContractResultBuilder->getResultDataFromTransactionResponse($transactionResponse);
    }
}
