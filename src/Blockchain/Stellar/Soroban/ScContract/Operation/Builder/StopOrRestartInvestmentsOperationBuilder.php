<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
