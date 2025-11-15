<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Domain\Contract\ContractFunctions;
use App\Domain\Token\Service\TokenNormalizer;
use App\Entity\Contract\ContractBalanceMovement;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class ContractAvailableToReserveFundOperationBuilder
{
    public function __construct(
        private readonly TokenNormalizer $tokenNormalizer,
    ) {
    }

    public function build(ContractBalanceMovement $contractBalanceMovement): InvokeHostFunctionOperation
    {
        $contract = $contractBalanceMovement->getContract();
        $amountI128 = $this->tokenNormalizer->normalizeTokenValue($contractBalanceMovement->getAmount(), $contract->getToken()->getDecimals());
        $invokeContractHostFunction = new InvokeContractHostFunction(
            $contract->getAddress(),
            ContractFunctions::move_funds_to_the_reserve->name,
            [
                XdrSCVal::forI128Parts($amountI128->getHi(), $amountI128->getLo()),
            ]
        );

        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);

        return $builder->build();
    }
}
