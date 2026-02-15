<?php

declare(strict_types=1);

namespace App\Domain\Contract\Service;

use App\Domain\Token\Service\TokenNormalizer;
use App\Domain\UserContract\Service\ClaimableDateCalculator;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\Xdr\XdrSCMapEntry;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class ContractActivationInvestmentParamsBuilder
{
    const CLAIM_BLOCK_DAYS_KEY = 'claim_block_days';
    const GOAL_KEY = 'goal';
    const I_RATE_KEY = 'i_rate';
    const MIN_PER_INVESTMENT_KEY = 'min_per_investment';
    const RETURN_MONTHS_KEY = 'return_months';
    const RETURN_TYPE_KEY = 'return_type';

    public function __construct(
        private readonly ClaimableDateCalculator $claimableDateCalculator,
        private readonly TokenNormalizer $tokenNormalizer
    ) {
    }

    public function buildInvestmentParams(Contract $contract): XdrSCVal
    {
        $claimMonths = $contract->getClaimMonths();
        $days = $this->claimableDateCalculator->getDaysToClaim($claimMonths);
        $rate = $contract->getRate() * 100;

        $goalI128 = $this->tokenNormalizer->normalizeTokenValue($contract->getGoal(), $contract->getToken()->getDecimals());
        $minPerInvestmentI128 = $this->tokenNormalizer->normalizeTokenValue($contract->getMinPerInvestment(), $contract->getToken()->getDecimals());

        return XdrSCVal::forMap([
            new XdrSCMapEntry(
                XdrSCVal::forSymbol(self::CLAIM_BLOCK_DAYS_KEY),
                XdrSCVal::forU64($days)
            ),
            new XdrSCMapEntry(
                XdrSCVal::forSymbol(self::GOAL_KEY),
                XdrSCVal::forI128Parts($goalI128->getHi(), $goalI128->getLo())
            ),
            new XdrSCMapEntry(
                XdrSCVal::forSymbol(self::I_RATE_KEY),
                XdrSCVal::forU32((int) $rate)
            ),
            new XdrSCMapEntry(
                XdrSCVal::forSymbol(self::MIN_PER_INVESTMENT_KEY),
                XdrSCVal::forI128Parts($minPerInvestmentI128->getHi(), $minPerInvestmentI128->getLo())
            ),
            new XdrSCMapEntry(
                XdrSCVal::forSymbol(self::RETURN_MONTHS_KEY),
                XdrSCVal::forU32($contract->getReturnMonths())
            ),
            new XdrSCMapEntry(
                XdrSCVal::forSymbol(self::RETURN_TYPE_KEY),
                XdrSCVal::forU32($contract->getReturnType())
            ),
        ]);
    }
}