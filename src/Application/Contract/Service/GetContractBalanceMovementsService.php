<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractBalanceMovementTransformer;
use App\Entity\User;
use App\Persistence\Contract\Doctrine\ContractBalanceMovementDoctrineStorage;
use App\Presentation\Contract\DTO\Output\ContractBalanceMovementDtoOutput;

class GetContractBalanceMovementsService
{
    public function __construct(
        private readonly ContractBalanceMovementDoctrineStorage $contractBalanceMovementStorage,
        private readonly ContractBalanceMovementTransformer $contractBalanceMovementTransformer
    ){}

    /**
     * @return ContractBalanceMovementDtoOutput[]
     */
    public function getContractBalanceMovements(User $user): array
    {
        $results = ($user->isAdmin())
            ? $this->contractBalanceMovementStorage->getAll()
            : $this->contractBalanceMovementStorage->getByUser($user)
        ;

        return $this->contractBalanceMovementTransformer->fromEntitiesToOutputDtos($results);
    }
}
