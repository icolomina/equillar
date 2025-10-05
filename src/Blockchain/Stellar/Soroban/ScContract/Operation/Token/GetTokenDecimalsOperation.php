<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
