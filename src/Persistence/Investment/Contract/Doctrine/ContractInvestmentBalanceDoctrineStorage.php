<?php

namespace App\Persistence\Investment\Contract\Doctrine;

use App\Entity\Investment\ContractInvestment;
use App\Entity\Investment\ContractInvestmentBalance;
use App\Persistence\Investment\Contract\ContractInvestmentBalanceStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class ContractInvestmentBalanceDoctrineStorage extends AbstractDoctrineStorage implements ContractInvestmentBalanceStorageInterface
{
    public function getBalanceByContractInvestment(ContractInvestment $contractInvestment): array
    {
        return $this->em->getRepository(ContractInvestmentBalance::class)->findBy(['contractInvestment' => $contractInvestment]);
    }

    public function getLastBalanceByContractInvestment(ContractInvestment $contractInvestment): ?ContractInvestmentBalance
    {
        return $this->em->getRepository(ContractInvestmentBalance::class)->findOneBy(
            ['contractInvestment' => $contractInvestment],
            ['createdAt' => 'DESC']
        );
    }

    public function getLastSuccesfulBalanceByContractInvestment(ContractInvestment $contractInvestment): ?ContractInvestmentBalance
    {
        return $this->em->getRepository(ContractInvestmentBalance::class)->findOneBy(
            [
                'contractInvestment' => $contractInvestment,
                'status' => 'SUCCESS'
            ],
            ['createdAt' => 'DESC']
        );
    }
}
