<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Entity\User;
use App\Persistence\Contract\ContractBalanceStorageInterface;
use App\Persistence\Contract\ContractStorageInterface;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;

class GetContractsService
{

    public function __construct(
        private readonly ContractStorageInterface $contractStorage,
        private readonly ContractEntityTransformer $contractEntityTransformer,
        private readonly ContractBalanceStorageInterface $contractBalanceStorage
    ){}

    /**
     * @return ContractDtoOutput[]
     */
    public function getContracts(User $user): array
    {
        $contracts = ($user->isAdmin()) 
            ? $this->contractStorage->getAllContracts() 
            : $this->contractStorage->getContractsByIssuer($user)
        ;
        
        $contractsOutput = [];
        foreach($contracts as $contract){
            $lastBalance = ($contract->getContractBalances()->isEmpty()) ? null : $contract->getContractBalances()->first();
            $contractsOutput[] = $this->contractEntityTransformer->fromEntityToOutputDto($contract, $lastBalance);
        }

        
        return $contractsOutput;
    } 

    public function getAvailableContracts(User $user): array
    {
        $contracts = $this->contractStorage->getInitializedContracts();

        $contractsOutput = [];
        foreach($contracts as $contract){
            $contractInvestmentBalance = $this->contractBalanceStorage->getLastBalanceByContract($contract);
            $contractsOutput[] = $this->contractEntityTransformer->fromEntityToOutputDto($contract, $contractInvestmentBalance);
        }

        return $contractsOutput;
    }

}
