<?php

namespace App\Application\Investment\Contract\Service;

use App\Application\Investment\Contract\Transformer\ContractInvestmentEntityTransformer;
use App\Entity\Contract;
use App\Persistence\Investment\Contract\ContractInvestmentStorageInterface;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;
use App\Stellar\Soroban\Contract\InteractManager;

class StopContractInvestmentsService
{
    public function __construct(
        
    ){}

    public function stopInvestments(Contract $contract)
    {
        
    }
}