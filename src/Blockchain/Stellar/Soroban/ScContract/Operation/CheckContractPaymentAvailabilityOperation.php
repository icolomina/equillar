<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
