<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\InstallContractService;
use App\Domain\ScContract\Investment\Service\GenerateConstructorArgumentsService;
use App\Entity\Investment\ContractInvestment;
use App\Presentation\Contract\DTO\Input\InitializeContractDtoInput;

class InitializeContractOperation
{
    public function __construct(
        private readonly InstallContractService $installContractService,
        private readonly StellarAccountLoader $stellarAccountLoader,
        private readonly GenerateConstructorArgumentsService $generateConstructorArgumentsService,
        private readonly string $wasmId
    ){}

    public function initializeContract(ContractInvestment $contract, InitializeContractDtoInput $initializeContractDtoInput): string
    {
        $constructorArguments = $this->generateConstructorArgumentsService->generateConstructorArguments(
            $contract, 
            $initializeContractDtoInput, 
            $this->stellarAccountLoader->getAccount()->getAccountId()
        );

        return $this->installContractService->install($this->wasmId, $constructorArguments);
    }
}
