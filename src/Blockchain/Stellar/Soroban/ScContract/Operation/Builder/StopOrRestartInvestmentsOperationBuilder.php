<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractPauseOrResumeTypes;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;

class StopOrRestartInvestmentsOperationBuilder
{
    public function build(Contract $contract, string $type): InvokeHostFunctionOperation
    {
        $contractFunction = ($type === ContractPauseOrResumeTypes::PAUSE->name) ? ContractFunctions::stop_investments->name : ContractFunctions::restart_investments->name;

        $invokeContractHostFunction = new InvokeContractHostFunction($contract->getAddress(), $contractFunction);
        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);

        return $builder->build();
    }
}
