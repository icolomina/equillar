<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractReserveFundContributionTransformer;
use App\Entity\Contract\Contract;
use App\Entity\User;
use App\Persistence\Contract\ContractReserveFundContributionStorageInterface;

class GetContractReserveFundContributionsService
{
    public function __construct(
        private readonly ContractReserveFundContributionStorageInterface $contractReserveFundContributionStorage,
        private readonly ContractReserveFundContributionTransformer $contractReserveFundContributionTransformer
    ){}

    public function getContractReserveFundContributions(Contract $contract): array
    {
        $reserveFundContributions = $this->contractReserveFundContributionStorage->getByContract($contract);
        return $this->contractReserveFundContributionTransformer->fromEntitiesToOutputDtos($reserveFundContributions);
    }

    public function getReserveFundContributions(User $user): array
    {
        $reserveFundContributions = $this->contractReserveFundContributionStorage->getByUser($user);
        return $this->contractReserveFundContributionTransformer->fromEntitiesToOutputDtos($reserveFundContributions);
    }
}
