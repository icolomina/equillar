<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractPauseOrResumeTypes;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Soroban\Address;

class StopOrRestartInvestmentsOperationBuilder
{
    public function __construct(
        private readonly StellarAccountLoader $stellarAccountLoader,
    ) {
    }

    public function build(Contract $contract, string $type): InvokeHostFunctionOperation
    {
        $contractFunction = ($type === ContractPauseOrResumeTypes::PAUSE->name) 
            ? ContractFunctions::pause->name 
            : ContractFunctions::unpause->name
        ;

        $invokeContractHostFunction = new InvokeContractHostFunction($contract->getAddress(), $contractFunction, [
            Address::fromAccountId($this->stellarAccountLoader->getAccount()->getAccountId())->toXdrSCVal()
        ]);
        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);

        return $builder->build();
    }
}
