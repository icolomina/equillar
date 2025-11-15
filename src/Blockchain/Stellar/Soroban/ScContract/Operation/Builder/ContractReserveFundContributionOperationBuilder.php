<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Domain\Contract\ContractFunctions;
use App\Domain\Token\Service\TokenNormalizer;
use App\Entity\Contract\ContractReserveFundContribution;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class ContractReserveFundContributionOperationBuilder
{
    public function __construct(
        private readonly TokenNormalizer $tokenNormalizer,
    ) {
    }

    public function build(ContractReserveFundContribution $contractReserveFundContribution): InvokeHostFunctionOperation
    {
        $contract = $contractReserveFundContribution->getContract();
        $amountI128 = $this->tokenNormalizer->normalizeTokenValue($contractReserveFundContribution->getAmount(), $contract->getToken()->getDecimals());
        $invokeContractHostFunction = new InvokeContractHostFunction(
            $contract->getAddress(),
            ContractFunctions::add_company_transfer->name,
            [
                XdrSCVal::forI128Parts($amountI128->getHi(), $amountI128->getLo()),
            ]
        );

        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);

        return $builder->build();
    }
}
