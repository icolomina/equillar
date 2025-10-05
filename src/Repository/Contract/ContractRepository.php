<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Repository\Contract;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractBalance;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contract>
 */
class ContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contract::class);
    }

    /**
     * @return Contract[]
     */
    public function findContractsByIssuerWithBalance(User $user): array
    {
        $joinBalance = sprintf('cb.id = (SELECT MAX(cb2.id) FROM %s cb2 WHERE cb2.contract = c)', ContractBalance::class);
        $joinWithdrawalRequest = sprintf(
            'cwr.id = (SELECT MAX(cwr2.id) FROM %s cwr2 WHERE cwr2.contract = c and cwr2.status <> :rejected_status and cwr2.status <> :approved_status)',
            ContractWithdrawalRequest::class
        );

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c, cb')
            ->from(Contract::class, 'c')
            ->andWhere('c.issuer = :issuer')
            ->leftJoin('c.contractBalances', 'cb', Join::WITH, $joinBalance)
            ->leftJoin('c.contractWithdrawalRequests', 'cwr', Join::WITH, $joinWithdrawalRequest)
            ->setParameter('issuer', $user)
            ->setParameter('rejected_status', 'REJECTED')
            ->setParameter('approved_status', 'APPROVED')
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function findAllContractsWithBalance(): array
    {
        $joinBalance = sprintf('cb.id = (SELECT MAX(cb2.id) FROM %s cb2 WHERE cb2.contract = c)', ContractBalance::class);
        $joinWithdrawalRequest = sprintf(
            'cwr.id = (SELECT MAX(cwr2.id) FROM %s cwr2 WHERE cwr2.contract = c and cwr2.status <> :rejected_status and cwr2.status <> :approved_status)',
            ContractWithdrawalRequest::class
        );

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c, cb')
            ->from(Contract::class, 'c')
            ->leftJoin('c.contractBalances', 'cb', Join::WITH, $joinBalance)
            ->leftJoin('c.contractWithdrawalRequests', 'cwr', Join::WITH, $joinWithdrawalRequest)
            ->setParameter('rejected_status', 'REJECTED')
            ->setParameter('approved_status', 'APPROVED')
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
