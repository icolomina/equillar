<?php

namespace App\Repository\Investment;

use App\Domain\Contract\Investment\UserContractInvestmentStatus;
use App\Entity\Investment\UserContractInvestment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserContractInvestment>
 */
class UserContractInvestmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserContractInvestment::class);
    }

    public function findClaimableCandidates(\DateTimeImmutable $claimableFrom, \DateTimeImmutable $lastPaymentFrom): Query
    {
        $qb = $this->createQueryBuilder('uc');
        return $qb
            ->innerJoin('uc.contract', 'c')
            ->andWhere($qb->expr()->gte('uc.claimableAt', ':claimableFrom'))
            ->andWhere('c.status in (:claimableStatus, :cashFlowingStatus)')
            ->andWhere(
                $qb->expr()->orX(
                    'uc.lastPaymentReceivedAt is null',
                    'uc.lastPaymentreceivedAt < :lastPaymentFrom'
                )
            )
            ->setParameter('claimableFrom', $claimableFrom)
            ->setParameter('claimableStatus', UserContractInvestmentStatus::CLAIMABLE->name)
            ->setParameter('cashFlowingStatus', UserContractInvestmentStatus::CASH_FLOWING->name)
            ->setParameter('lastPaymentFrom', $lastPaymentFrom)
            ->getQuery()
        ;
    }
}
