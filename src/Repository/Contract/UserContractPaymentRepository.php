<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Repository\Contract;

use App\Entity\Contract\Contract;
use App\Entity\Contract\UserContractPayment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserContractPayment>
 */
class UserContractPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserContractPayment::class);
    }

    public function findUserContractPayments(User $user): array
    {
        $qb = $this->createQueryBuilder('ucp');

        return $qb
            ->innerJoin('ucp.userContract', 'uc')
            ->andWhere($qb->expr()->eq('uc.usr', ':user'))
            ->setParameter('user', $user)
            ->orderBy('uc.createdAt', 'desc')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    public function sumUserContractPaymentsByContract(Contract $contract): int|float|null
    {
        $qb = $this->createQueryBuilder('ucp');

        return $qb
            ->select('SUM(ucp.totalClaimed) as total')
            ->innerJoin('ucp.userContract', 'uc')
            ->andWhere($qb->expr()->eq('uc.contract', ':contract'))
            ->setParameter('contract', $contract)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
