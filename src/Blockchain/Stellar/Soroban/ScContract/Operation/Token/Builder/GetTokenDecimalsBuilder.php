<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Token\Builder;

use App\Domain\Contract\ContractFunctions;
use App\Entity\Token;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;

class GetTokenDecimalsBuilder
{
    public function build(Token $token): InvokeHostFunctionOperation
    {
        $invokeContractHostFunction = new InvokeContractHostFunction($token->getAddress(), ContractFunctions::decimals->name);

        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);

        return $builder->build();
    }
}
