<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Entity\Investment\ContractInvestment;
use App\Persistence\Investment\Contract\ContractInvestmentStorageInterface;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;

class ApproveContractService
{
    public function __construct(
        private readonly ContractInvestmentStorageInterface $contractInvestmentStorage,
        private readonly ContractEntityTransformer $contractEntityTransformer,
    ){}

    public function approveContractInvestment(ContractInvestment $contractInvestment): ContractDtoOutput
    {
        $this->contractInvestmentStorage->markContractAsApproved($contractInvestment);
        return $this->contractEntityTransformer->fromEntityToOutputDto($contractInvestment);

    }
}
