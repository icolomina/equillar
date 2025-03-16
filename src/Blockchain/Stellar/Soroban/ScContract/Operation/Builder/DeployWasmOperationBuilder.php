<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\UploadContractWasmHostFunction;

class DeployWasmOperationBuilder
{
    public function build(string $wasmCode): InvokeHostFunctionOperation
    {
        $uploadContractHostFunction = new UploadContractWasmHostFunction($wasmCode);
        $builder = new InvokeHostFunctionOperationBuilder($uploadContractHostFunction);
        return $builder->build();
    }
}
