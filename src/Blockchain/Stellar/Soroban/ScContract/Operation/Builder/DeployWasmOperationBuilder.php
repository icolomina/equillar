<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
