<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
