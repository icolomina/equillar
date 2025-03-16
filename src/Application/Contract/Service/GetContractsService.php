<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Domain\Contract\Service\AvailableContractsInvestmentFilter;
use App\Entity\User;
use App\Persistence\Investment\Contract\ContractInvestmentBalanceStorageInterface;
use App\Persistence\Investment\Contract\ContractInvestmentStorageInterface;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;

class GetContractsService
{

    public function __construct(
        private readonly ContractInvestmentStorageInterface $contractInvestmentStorage,
        private readonly ContractEntityTransformer $contractEntityTransformer,
        private readonly AvailableContractsInvestmentFilter $availableContractsInvestmentFilter,
        private readonly ContractInvestmentBalanceStorageInterface $contractInvestmentBalanceStorage
    ){}

    /**
     * @return ContractDtoOutput[]
     */
    public function getContracts(User $user): array
    {
        $contracts = ($user->isAdmin()) 
            ? $this->contractInvestmentStorage->getAllContracts() 
            : $this->contractInvestmentStorage->getContractsByIssuer($user)
        ;
        
        return $this->contractEntityTransformer->fromEntitiesToOutputDtos($contracts);
    } 

    public function getAvailableContracts(User $user): array
    {
        $contracts = $this->contractInvestmentStorage->getInitializedContracts();
        $userContracts = $user->getContracts();

        $contractsOutput = [];
        foreach($contracts as $contract){
            if($this->availableContractsInvestmentFilter->isAvailableContractInvestment($contract, $userContracts->toArray())){
                $contractInvestmentBalance = $this->contractInvestmentBalanceStorage->getLastBalanceByContractInvestment($contract);
                $contractsOutput[] = $this->contractEntityTransformer->fromEntityToOutputDto($contract, $contractInvestmentBalance);
            }
        }

        return $contractsOutput;
    }

}
