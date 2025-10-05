<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Domain\Contract\ContractFunctions;
use App\Domain\Token\Service\TokenNormalizer;
use App\Entity\Contract\ContractReserveFundContribution;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class ContractReserveFundContributionOperationBuilder
{
    public function __construct(
        private readonly TokenNormalizer $tokenNormalizer,
    ) {
    }

    public function build(ContractReserveFundContribution $contractReserveFundContribution): InvokeHostFunctionOperation
    {
        $contract = $contractReserveFundContribution->getContract();
        $amountI128 = $this->tokenNormalizer->normalizeTokenValue($contractReserveFundContribution->getAmount(), $contract->getToken()->getDecimals());
        $invokeContractHostFunction = new InvokeContractHostFunction(
            $contract->getAddress(),
            ContractFunctions::add_company_transfer->name,
            [
                XdrSCVal::forI128Parts($amountI128->getHi(), $amountI128->getLo()),
            ]
        );

        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);

        return $builder->build();
    }
}
