<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use Soneso\StellarSDK\CreateContractHostFunction;
use Soneso\StellarSDK\CreateContractWithConstructorHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Soroban\Address;

class InstallContractOperationBuilder
{
    public function __construct(
        private readonly StellarAccountLoader $stellarAccountLoader
    ){}

    public function build(string $wasmId, ?array $constructorArgs): InvokeHostFunctionOperation
    {
        $createContractHostFunction = (empty($constructorArgs)) 
            ? new CreateContractHostFunction(Address::fromAccountId($this->stellarAccountLoader->getAccount()->getAccountId()), $wasmId)
            : new CreateContractWithConstructorHostFunction(
                Address::fromAccountId($this->stellarAccountLoader->getAccount()->getAccountId()),
                $wasmId,
                $constructorArgs
            )
        ;

        $builder = new InvokeHostFunctionOperationBuilder($createContractHostFunction);
        return $builder->build();

    }
}
