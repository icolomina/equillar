<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Repository\Contract;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractReserveFundContribution;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContractReserveFundContribution>
 */
class ContractReserveFundContributionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractReserveFundContribution::class);
    }

    public function sumContributionsByContract(Contract $contract): int|float|null
    {
        $qb = $this->createQueryBuilder('crf');

        return $qb
            ->select('SUM(crf.amount) as total')
            ->andWhere($qb->expr()->eq('crf.contract', ':contract'))
            ->setParameter('contract', $contract)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findReserveFundContributionsByIssuer(User $user): array
    {
        $qb = $this->createQueryBuilder('crf');

        return $qb
            ->innerJoin('crf.contract', 'c')
            ->andWhere($qb->expr()->eq('c.issuer', ':issuer'))
            ->setParameter('issuer', $user)
            ->orderBy('crf.id', 'desc')
            ->getQuery()
            ->getResult()
        ;
    }
}
