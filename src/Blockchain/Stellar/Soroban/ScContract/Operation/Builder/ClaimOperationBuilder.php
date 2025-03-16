<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Domain\Contract\ContractFunctions;
use App\Entity\Investment\UserContractInvestment;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Soroban\Address;

class ClaimOperationBuilder
{
    public function build(UserContractInvestment $userContractInvestment): InvokeHostFunctionOperation
    {
        $invokeContractHostFunction = new InvokeContractHostFunction($userContractInvestment->getContract()->getAddress(), ContractFunctions::claim->case, [
            Address::fromAccountId($userContractInvestment->getUserWallet()->getAddress())->toXdrSCVal()
        ]);
        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);
        return $builder->build();
    }
}
