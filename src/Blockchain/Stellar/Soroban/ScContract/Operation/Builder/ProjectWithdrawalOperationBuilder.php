<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Domain\Contract\ContractFunctions;
use App\Domain\I128;
use App\Domain\Token\Service\TokenNormalizer;
use App\Entity\Investment\ContractInvestment;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Soroban\Address;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class ProjectWithdrawalOperationBuilder
{

    public function __construct(
        private readonly StellarAccountLoader $stellarAccountLoader,
        private readonly TokenNormalizer $tokenNormalizer
    ){}

    public function build(ContractInvestment $contractInvestment, float $amount): InvokeHostFunctionOperation
    {
        $amountI128 = $this->tokenNormalizer->normalizeTokenValue($amount, $contractInvestment->getToken()->getDecimals());
        $invokeContractHostFunction = new InvokeContractHostFunction(
            $contractInvestment->getAddress(), 
            ContractFunctions::project_withdrawn->name,
            [
                Address::fromAccountId($this->stellarAccountLoader->getAccount()->getAccountId())->toXdrSCVal(),
                XdrSCVal::forI128Parts($amountI128->getLo(), $amountI128->getHi())
            ]
        );

        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);
        return $builder->build();
    }
}
