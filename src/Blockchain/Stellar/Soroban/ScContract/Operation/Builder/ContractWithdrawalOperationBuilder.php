<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Token\Service\TokenNormalizer;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class ContractWithdrawalOperationBuilder
{

    public function __construct(
        private readonly TokenNormalizer $tokenNormalizer
    ){}

    public function build(Contract $contract, float $amount): InvokeHostFunctionOperation
    {
        $amountI128 = $this->tokenNormalizer->normalizeTokenValue($amount, $contract->getToken()->getDecimals());
        $invokeContractHostFunction = new InvokeContractHostFunction(
            $contract->getAddress(), 
            ContractFunctions::single_withdrawn->name,
            [
                XdrSCVal::forI128Parts($amountI128->getHi(), $amountI128->getLo())
            ]
        );

        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);
        return $builder->build();
    }
}
