<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Domain\Contract\Service\ContractActivationInvestmentParamsBuilder;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\CreateContractWithConstructorHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Soroban\Address;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class ContractActivationOperationBuilder
{
    public function __construct(
        private readonly StellarAccountLoader $stellarAccountLoader,
        private readonly ContractActivationInvestmentParamsBuilder $contractActivationInvestmentParamsBuilder
    ) {
    }

    public function build(Contract $contract, string $wasmId): InvokeHostFunctionOperation
    {
        $investmentParams = $this->contractActivationInvestmentParamsBuilder->buildInvestmentParams($contract);

        $constructorArgs = [
            Address::fromAccountId($this->stellarAccountLoader->getAccount()->getAccountId())->toXdrSCVal(),
            Address::fromAccountId($contract->getProjectAddress())->toXdrSCVal(),
            Address::fromContractId($contract->getToken()->getAddress())->toXdrSCVal(),
            XdrSCVal::forString($contract->getUri()),
            XdrSCVal::forString($contract->getLabel()),
            XdrSCVal::forString($contract->getSymbol()),
            $investmentParams,
        ];

        $createContractHostFunction = new CreateContractWithConstructorHostFunction(
            Address::fromAccountId($this->stellarAccountLoader->getAccount()->getAccountId()),
            $wasmId,
            $constructorArgs
        );

        $builder = new InvokeHostFunctionOperationBuilder($createContractHostFunction);

        return $builder->build();
    }
}
