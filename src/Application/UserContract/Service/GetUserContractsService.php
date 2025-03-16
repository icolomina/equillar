<?php

namespace App\Application\UserContract\Service;

use App\Application\UserContract\Transformer\UserContractEntityTransformer;
use App\Entity\Investment\UserContractInvestment;
use App\Entity\User;
use App\Persistence\Investment\UserContract\UserContractInvestmentStorageInterface;
use App\Presentation\UserContract\DTO\Output\UserContractDtoOutput;

class GetUserContractsInvestmentService
{

    public function __construct(
        private readonly UserContractInvestmentStorageInterface $userContractInvestmentStorage,
        private readonly UserContractEntityTransformer $userContractInvestmentEntityTransformer
    ){}

    public function getUserContracts(User $user): array
    {
        $userContracts = $this->userContractInvestmentStorage->getByUser($user);
        return $this->userContractInvestmentEntityTransformer->fromEntitiesToOutputDtos($userContracts);
    }

    public function getUserContract(UserContractInvestment $userContract): UserContractDtoOutput
    {
        return $this->userContractInvestmentEntityTransformer->fromEntityToOutputDto($userContract);
    }
}
