<?php

namespace App\Domain\ScContract\Investment\Service;

use App\Domain\I128;
use App\Domain\Token\Service\TokenNormalizer;
use App\Domain\UserContract\Service\ClaimableDateCalculator;
use App\Entity\Investment\ContractInvestment;
use App\Presentation\Contract\DTO\Input\InitializeContractDtoInput;
use Soneso\StellarSDK\Soroban\Address;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class GenerateConstructorArgumentsService
{

    public function __construct(
        private readonly ClaimableDateCalculator $claimableDateCalculator,
        private readonly TokenNormalizer $tokenNormalizer
    ){}

    public function generateConstructorArguments(ContractInvestment $contract, InitializeContractDtoInput $initializeContractDtoInput, string $stellarAdminAccountId)
    {
        $claimMonts    = $contract->getClaimMonths();
        $days          = $this->claimableDateCalculator->getDaysToClaim($claimMonts);
        $rate          = $contract->getRate() * 100;

        $goal          = $this->tokenNormalizer->normalizeTokenValue($contract->getGoal(), $contract->getToken()->getDecimals());
        $goalI128      = new I128($goal);

        $minPerInvestment     = $this->tokenNormalizer->normalizeTokenValue($initializeContractDtoInput->minPerInvestment, $contract->getToken()->getDecimals());
        $minPerInvestmentI128 = new I128($minPerInvestment);

        return[
            Address::fromAccountId($stellarAdminAccountId)->toXdrSCVal(),
            Address::fromAccountId($initializeContractDtoInput->projectAddress)->toXdrSCVal(),
            Address::fromContractId($contract->getToken()->getAddress())->toXdrSCVal(),
            XdrSCVal::forU32((int)$rate),
            XdrSCVal::forU64($days),
            XdrSCVal::forI128Parts($goalI128->getLo(), $goalI128->getHi()),
            XdrSCVal::forU32($initializeContractDtoInput->returnType),
            XdrSCVal::forU32($initializeContractDtoInput->returnMonths),
            XdrSCVal::forI128Parts($minPerInvestmentI128->getLo(), $minPerInvestmentI128->getHi()),
        ];
    }
}
