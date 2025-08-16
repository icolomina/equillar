<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Domain\Contract\ContractFunctions;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;

class GetContractBalanceOperationBuilder
{

    public function build(Contract $contract): InvokeHostFunctionOperation
    {
        $invokeContractHostFunction = new InvokeContractHostFunction($contract->getAddress(), ContractFunctions::get_contract_balance->name);
        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);
        return $builder->build();
    }
}
