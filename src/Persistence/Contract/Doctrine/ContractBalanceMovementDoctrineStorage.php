<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\Contract\Doctrine;

use App\Entity\Contract\ContractBalanceMovement;
use App\Entity\User;
use App\Persistence\Contract\ContractBalanceMovementStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class ContractBalanceMovementDoctrineStorage extends AbstractDoctrineStorage implements ContractBalanceMovementStorageInterface
{
    public function getAll(): array
    {
        return $this->em->getRepository(ContractBalanceMovement::class)->findAll();
    }

    public function getByUser(User $user): array
    {
        return $this->em->getRepository(ContractBalanceMovement::class)->findByUser($user);
    }
}
