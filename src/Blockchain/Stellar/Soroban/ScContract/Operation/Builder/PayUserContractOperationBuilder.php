<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Domain\Contract\ContractFunctions;
use App\Entity\Contract\UserContract;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class PayUserContractOperationBuilder
{
    public function build(UserContract $userContract): InvokeHostFunctionOperation
    {
        $invokeContractHostFunction = new InvokeContractHostFunction($userContract->getContract()->getAddress(), ContractFunctions::process_investor_payment->name, [
            XdrSCVal::forU32($userContract->getTokenId()),
        ]);
        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);

        return $builder->build();
    }
}
