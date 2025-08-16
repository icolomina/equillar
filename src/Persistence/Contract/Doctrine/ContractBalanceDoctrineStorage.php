<?php

namespace App\Persistence\Contract\Doctrine;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractBalance;
use App\Persistence\Contract\ContractBalanceStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class ContractBalanceDoctrineStorage extends AbstractDoctrineStorage implements ContractBalanceStorageInterface
{
    public function getBalanceByContract(Contract $contract): array
    {
        return $this->em->getRepository(ContractBalance::class)->findBy(['contract' => $contract]);
    }

    public function getLastBalanceByContract(Contract $contract): ?ContractBalance
    {
        return $this->em->getRepository(ContractBalance::class)->findOneBy(
            ['contract' => $contract],
            ['createdAt' => 'DESC']
        );
    }

    public function getLastSuccesfulBalanceByContract(Contract $contract): ?ContractBalance
    {
        return $this->em->getRepository(ContractBalance::class)->findOneBy(
            [
                'contract' => $contract,
                'status' => 'CONFIRMED'
            ],
            ['createdAt' => 'DESC']
        );
    }
}
