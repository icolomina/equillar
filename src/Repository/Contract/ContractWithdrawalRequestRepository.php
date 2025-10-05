<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Repository\Contract;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContractWithdrawalRequest>
 */
class ContractWithdrawalRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractWithdrawalRequest::class);
    }

    public function findWithdrawalRequestsByContract(Contract $contract): array
    {
        $qb = $this->createQueryBuilder('cwr');
        $qb
            ->select('cwr')
            ->leftJoin('cwr.withdrawalApproval', 'cwa')
            ->andWhere($qb->expr()->eq('cwr.contract', ':contract'))
            ->setParameter('contract', $contract)
            ->setMaxResults(20)
            ->orderBy('cwr.requestedAt', 'desc')
        ;

        return $qb->getQuery()->getResult();
    }

    public function findWithdrawalRequestsByUser(User $user): array
    {
        $qb = $this->createQueryBuilder('cwr');
        $qb
            ->select('cwr')
            ->innerJoin('cwr.contract', 'c')
            ->leftJoin('cwr.withdrawalApproval', 'cwa')
            ->andWhere($qb->expr()->eq('c.issuer', ':issuer'))
            ->setParameter('issuer', $user)
            ->setMaxResults(20)
            ->orderBy('cwr.requestedAt', 'desc')
        ;

        return $qb->getQuery()->getResult();
    }

    public function sumApprovedWithdrawalsAmountByContract(Contract $contract): int|float|null
    {
        $qb = $this->createQueryBuilder('cwr');

        return $qb
            ->select('SUM(cwr.requestedAmount) as amount')
            ->innerJoin('cwr.withdrawalApproval', 'cwa')
            ->andWhere($qb->expr()->eq('cwr.contract', ':contract'))
            ->andWhere($qb->expr()->isNotNull('cwa.approvedAt'))
            ->setParameter('contract', $contract)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
