<?php

namespace App\Application\Investment\Contract\Service;

use App\Application\Contract\Transformer\ContractBalanceEntityTransformer;
use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Investment\ContractInvestment;
use App\Entity\User;
use App\Persistence\Investment\Contract\ContractInvestmentBalanceStorageInterface;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;
use App\Presentation\Contract\DTO\Output\ContractInvestmentBalanceDtoOutput;

class EditContractInvestmentService
{
    public function __construct(
        private readonly ContractInvestmentBalanceStorageInterface $contractInvestmentBalanceStorage,
        private readonly ContractEntityTransformer $contractEntityTransformer,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly ContractBalanceEntityTransformer $contractInvestmentBalanceEntityTransformer
    ) {}

    public function editContract(ContractInvestment $contract, User $user): ContractDtoOutput
    {
        $contractBalance = ($contract->isInitialized()) 
            ? $this->contractInvestmentBalanceStorage->getLastSuccesfulBalanceByContractInvestment($contract)
            : null
        ;

        return $this->contractEntityTransformer->fromEntityToOutputDto($contract, $contractBalance);
    }

    public function editContractWithoutBalance(ContractInvestment $contract): ContractDtoOutput
    {
        return $this->contractEntityTransformer->fromEntityToOutputDto($contract, null);
    }

    private function generateContractBalanceOutput(ContractInvestment $contract, User $user): ?ContractInvestmentBalanceDtoOutput
    {
        $contractInvestmentBalance = $this->contractInvestmentBalanceStorage->getLastSuccesfulBalanceByContractInvestment($contract);
        return ($contractInvestmentBalance)
            ? $this->contractInvestmentBalanceEntityTransformer->fromEntityToOutputDto($contractInvestmentBalance, $user->isAdmin())
            : null
        ;
    }
}
