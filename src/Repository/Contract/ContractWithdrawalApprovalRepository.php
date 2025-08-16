<?php

namespace App\Repository\Contract;

use App\Entity\Contract\ContractWithdrawalApproval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContractWithdrawalApproval>
 */
class ContractWithdrawalApprovalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractWithdrawalApproval::class);
    }
}
