<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Domain\Token\Service\TokenNormalizer;
use App\Domain\UserContract\Service\ClaimableDateCalculator;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\CreateContractWithConstructorHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Soroban\Address;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class ContractActivationOperationBuilder
{
    public function __construct(
        private readonly ClaimableDateCalculator $claimableDateCalculator,
        private readonly TokenNormalizer $tokenNormalizer,
        private readonly StellarAccountLoader $stellarAccountLoader,
    ) {
    }

    public function build(Contract $contract, string $wasmId): InvokeHostFunctionOperation
    {
        $claimMonts = $contract->getClaimMonths();
        $days = $this->claimableDateCalculator->getDaysToClaim($claimMonts);
        $rate = $contract->getRate() * 100;

        $goalI128 = $this->tokenNormalizer->normalizeTokenValue($contract->getGoal(), $contract->getToken()->getDecimals());
        $minPerInvestmentI128 = $this->tokenNormalizer->normalizeTokenValue($contract->getMinPerInvestment(), $contract->getToken()->getDecimals());

        $constructorArgs = [
            Address::fromAccountId($this->stellarAccountLoader->getAccount()->getAccountId())->toXdrSCVal(),
            Address::fromAccountId($contract->getProjectAddress())->toXdrSCVal(),
            Address::fromContractId($contract->getToken()->getAddress())->toXdrSCVal(),
            XdrSCVal::forU32((int) $rate),
            XdrSCVal::forU64($days),
            XdrSCVal::forI128Parts($goalI128->getLo(), $goalI128->getHi()),
            XdrSCVal::forU32($contract->getReturnType()),
            XdrSCVal::forU32($contract->getReturnMonths()),
            XdrSCVal::forI128Parts($minPerInvestmentI128->getLo(), $minPerInvestmentI128->getHi()),
        ];

        $createContractHostFunction = new CreateContractWithConstructorHostFunction(
            Address::fromAccountId($this->stellarAccountLoader->getAccount()->getAccountId()),
            $wasmId,
            $constructorArgs
        );

        $builder = new InvokeHostFunctionOperationBuilder($createContractHostFunction);

        return $builder->build();
    }
}
