<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Domain\Contract\ContractFunctions;
use App\Domain\I128;
use App\Domain\Token\Service\TokenNormalizer;
use App\Domain\UserContract\Service\ClaimableDateCalculator;
use App\Entity\Investment\ContractInvestment;
use App\Presentation\Contract\DTO\Input\InitializeContractDtoInput;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Soroban\Address;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class InitializeContractOperationBuilder
{
    public function __construct(
        private readonly ClaimableDateCalculator $claimableDateCalculator,
        private readonly TokenNormalizer $tokenNormalizer,
        private readonly StellarAccountLoader $stellarAccountLoader
    ){}

    public function build(ContractInvestment $contract, InitializeContractDtoInput $initializeContractDtoInput): InvokeHostFunctionOperation
    {
        $claimMonts    = $contract->getClaimMonths();
        $days          = $this->claimableDateCalculator->getDaysToClaim($claimMonts);
        $rate          = $contract->getRate() * 100;

        $goalI128                 = $this->tokenNormalizer->normalizeTokenValue($contract->getGoal(), $contract->getToken()->getDecimals());
        $minPerInvestmentI128     = $this->tokenNormalizer->normalizeTokenValue($initializeContractDtoInput->minPerInvestment, $contract->getToken()->getDecimals());

        $invokeContractHostFunction = new InvokeContractHostFunction($contract->getAddress(), ContractFunctions::init->name, [
            Address::fromAccountId($this->stellarAccountLoader->getAccount()->getAccountId())->toXdrSCVal(),
            Address::fromAccountId($initializeContractDtoInput->projectAddress)->toXdrSCVal(),
            Address::fromContractId($contract->getToken()->getAddress())->toXdrSCVal(),
            XdrSCVal::forU32((int)$rate),
            XdrSCVal::forU64($days),
            XdrSCVal::forI128Parts($goalI128->getLo(), $goalI128->getHi()),
            XdrSCVal::forU32($initializeContractDtoInput->returnType),
            XdrSCVal::forU32($initializeContractDtoInput->returnMonths),
            XdrSCVal::forI128Parts($minPerInvestmentI128->getLo(), $minPerInvestmentI128->getHi()),
        ]);

        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);
        return $builder->build();
    }
}
