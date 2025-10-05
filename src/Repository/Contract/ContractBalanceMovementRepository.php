<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
