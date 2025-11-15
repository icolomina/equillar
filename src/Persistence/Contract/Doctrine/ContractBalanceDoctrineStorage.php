<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\Contract\Doctrine;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractBalance;
use App\Persistence\Contract\ContractBalanceStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class ContractBalanceDoctrineStorage extends AbstractDoctrineStorage implements ContractBalanceStorageInterface
{
    public function getBalanceByContract(Contract $contract): array
    {
        return $this->em->getRepository(ContractBalance::class)->findBy(['contract' => $contract]);
    }

    public function getLastBalanceByContract(Contract $contract): ?ContractBalance
    {
        return $this->em->getRepository(ContractBalance::class)->findOneBy(
            ['contract' => $contract],
            ['createdAt' => 'DESC']
        );
    }

    public function getLastSuccesfulBalanceByContract(Contract $contract): ?ContractBalance
    {
        return $this->em->getRepository(ContractBalance::class)->findOneBy(
            [
                'contract' => $contract,
                'status' => 'CONFIRMED',
            ],
            ['createdAt' => 'DESC']
        );
    }
}
