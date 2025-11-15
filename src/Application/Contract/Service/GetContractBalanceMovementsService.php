<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
