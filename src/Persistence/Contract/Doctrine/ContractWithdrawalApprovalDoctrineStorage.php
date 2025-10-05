<?php

namespace App\Persistence\Contract\Doctrine;

use App\Entity\Contract\ContractWithdrawalApproval;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Persistence\Contract\ContractWithdrawalApprovalStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class ContractWithdrawalApprovalDoctrineStorage extends AbstractDoctrineStorage implements ContractWithdrawalApprovalStorageInterface
{
    public function getByWithdrawalRequest(ContractWithdrawalRequest $withdrawalRequest): ?ContractWithdrawalApproval
    {
        return $this->em->getRepository(ContractWithdrawalApproval::class)->findOneBy(['contractWithdrawalRequest' => $withdrawalRequest]);
    }
}
