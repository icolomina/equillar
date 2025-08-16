<?php

namespace App\Persistence\ContractCode\Doctrine;

use App\Entity\ContractCode;
use App\Persistence\ContractCode\ContractCodeStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class ContractCodeDoctrineStorage extends AbstractDoctrineStorage implements ContractCodeStorageInterface
{
    public function getLastdeployedContractCode(): ?ContractCode
    {
        return $this->em->getRepository(ContractCode::class)->findOneBy(
            ['status' => 'STABLE'],
            ['id' => 'desc']
        );
    }
}
