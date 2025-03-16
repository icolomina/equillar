<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Domain\Contract\ContractFunctions;
use App\Entity\Contract;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;

class StopInvestmentsOperationBuilder
{
    public function build(Contract $contract): InvokeHostFunctionOperation
    {
        $invokeContractHostFunction = new InvokeContractHostFunction($contract->getAddress(), ContractFunctions::stop_investments->case);
        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);
        return $builder->build();
    }  
}
