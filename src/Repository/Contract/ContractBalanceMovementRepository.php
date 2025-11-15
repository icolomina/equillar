<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Repository\Contract;

use App\Entity\Contract\ContractBalanceMovement;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContractBalanceMovement>
 */
class ContractBalanceMovementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractBalanceMovement::class);
    }

    public function findByUser(User $user): array
    {
        $qb = $this->createQueryBuilder('cbm');
        return $qb
            ->innerJoin('cbm.contract', 'c')
            ->andWhere($qb->expr()->eq('c.issuer', ':user'))
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;

    }
}
