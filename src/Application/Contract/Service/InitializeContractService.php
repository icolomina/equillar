<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\Investment\InitializeContractOperation;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Entity\Investment\ContractInvestment;
use App\Persistence\Investment\Contract\ContractInvestmentStorageInterface;
use App\Presentation\Contract\DTO\Input\InitializeContractDtoInput;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;

class InitializeContractInvestmentService
{
    public function __construct(
        private readonly ContractInvestmentStorageInterface $contractInvestmentStorage,
        private readonly InitializeContractOperation $initializeContractOperation,
        private readonly ContractEntityTransformer $contractEntityTransformer
    ){}

    public function initializeContract(ContractInvestment $contract, InitializeContractDtoInput $initializeContractDtoInput): ContractDtoOutput
    {
        try{
            $contractAddress = $this->initializeContractOperation->initializeContract($contract, $initializeContractDtoInput);
            $this->contractInvestmentStorage->markContractAsInitalized(
                $contract, 
                $contractAddress,
                $initializeContractDtoInput->projectAddress, 
                $initializeContractDtoInput->returnType, 
                $initializeContractDtoInput->returnMonths, 
                $initializeContractDtoInput->minPerInvestment
            );
        }
        catch(TransactionExceptionInterface $ex){
            
        }

        return $this->contractEntityTransformer->fromEntityToOutputDto($contract);
    }
}
