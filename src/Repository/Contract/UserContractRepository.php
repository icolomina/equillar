<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Repository\Contract;

use App\Domain\UserContract\UserContractStatus;
use App\Entity\Contract\UserContract;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserContract>
 */
class UserContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserContract::class);
    }

    public function findClaimableCandidates(\DateTimeImmutable $claimableFrom, \DateTimeImmutable $lastPaymentFrom): Query
    {
        $qb = $this->createQueryBuilder('uc');

        return $qb
            ->innerJoin('uc.contract', 'c')
            ->andWhere($qb->expr()->lte('uc.claimableTs', ':claimableFrom'))
            ->andWhere('uc.status in (:claimableStatus, :cashFlowingStatus)')
            ->andWhere(
                $qb->expr()->orX(
                    'uc.lastPaymentReceivedAt is null',
                    'uc.lastPaymentReceivedAt < :lastPaymentFrom'
                )
            )
            ->setParameter('claimableFrom', $claimableFrom->getTimestamp())
            ->setParameter('claimableStatus', UserContractStatus::CLAIMABLE->name)
            ->setParameter('cashFlowingStatus', UserContractStatus::CASH_FLOWING->name)
            ->setParameter('lastPaymentFrom', $lastPaymentFrom)
            ->getQuery()
        ;
    }

    public function findUserPortfolioContracts(User $user): array
    {
        $qb = $this->createQueryBuilder('uc');

        return $qb
            ->andWhere($qb->expr()->eq('uc.usr', ':user'))
            ->andWhere($qb->expr()->neq('uc.status', ':status'))
            ->setParameter('user', $user)
            ->setParameter('status', UserContractStatus::FINISHED->name)
            ->getQuery()
            ->getResult()
        ;
    }
}
