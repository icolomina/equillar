<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Domain\Contract\ContractFunctions;
use App\Entity\Contract\ContractPaymentAvailability;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;

class CheckContractPaymentAvailabilityOperationBuilder
{
    public function build(ContractPaymentAvailability $contractPaymentAvailability): InvokeHostFunctionOperation
    {
        $contract   = $contractPaymentAvailability->getContract();
        $invokeContractHostFunction = new InvokeContractHostFunction(
            $contract->getAddress(), 
            ContractFunctions::check_reserve->name
        );

        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);
        return $builder->build();
    }
}
