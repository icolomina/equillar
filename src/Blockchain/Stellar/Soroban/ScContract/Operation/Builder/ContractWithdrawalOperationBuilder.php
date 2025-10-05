<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

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
        private readonly TokenNormalizer $tokenNormalizer,
    ) {
    }

    public function build(Contract $contract, float $amount): InvokeHostFunctionOperation
    {
        $amountI128 = $this->tokenNormalizer->normalizeTokenValue($amount, $contract->getToken()->getDecimals());
        $invokeContractHostFunction = new InvokeContractHostFunction(
            $contract->getAddress(),
            ContractFunctions::single_withdrawn->name,
            [
                XdrSCVal::forI128Parts($amountI128->getHi(), $amountI128->getLo()),
            ]
        );

        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);

        return $builder->build();
    }
}
