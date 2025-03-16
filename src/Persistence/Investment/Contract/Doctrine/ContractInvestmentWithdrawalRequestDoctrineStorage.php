<?php

namespace App\Persistence\Investment\Contract\Doctrine;

use App\Entity\Investment\ContractInvestmentWithdrawalRequest;
use App\Persistence\Investment\Contract\ContractInvestmentWithdrawalRequestStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class ContractInvestmentWithdrawalRequestDoctrineStorage extends AbstractDoctrineStorage implements ContractInvestmentWithdrawalRequestStorageInterface
{
    public function getWithdrawalRequestById(int $id): ?ContractInvestmentWithdrawalRequest
    {
        return $this->em->getRepository(ContractInvestmentWithdrawalRequest::class)->find($id);
    }
}
