<?php

namespace App\Persistence\Contract\Doctrine;

use App\Domain\Contract\ContractPaymentAvailabilityStatus;
use App\Entity\Contract\ContractPaymentAvailability;
use App\Persistence\Contract\ContractPaymentAvailabilityStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;


class ContractPaymentAvailabilityDoctrineStorage extends AbstractDoctrineStorage implements ContractPaymentAvailabilityStorageInterface
{
    public function getById(int $id): ?ContractPaymentAvailability
    {
       return $this->em->getRepository(ContractPaymentAvailability::class)->find($id);
    }

    public function getLastProcessedForContract(\App\Entity\Contract\Contract $contract): ?ContractPaymentAvailability
    {
        return $this->em->getRepository(ContractPaymentAvailability::class)->findOneBy(
            ['contract' => $contract, 'status' => ContractPaymentAvailabilityStatus::PROCESSED->name],
            ['id' => 'DESC']
        );
    }
    
}