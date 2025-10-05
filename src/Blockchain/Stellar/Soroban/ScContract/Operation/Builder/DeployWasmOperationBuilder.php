<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
