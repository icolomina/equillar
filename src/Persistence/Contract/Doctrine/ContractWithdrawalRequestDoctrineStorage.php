<?php

namespace App\Persistence\Contract\Doctrine;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Entity\User;
use App\Persistence\Contract\ContractWithdrawalRequestStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class ContractWithdrawalRequestDoctrineStorage extends AbstractDoctrineStorage implements ContractWithdrawalRequestStorageInterface
{
    public function getWithdrawalRequestById(int $id): ?ContractWithdrawalRequest
    {
        return $this->em->getRepository(ContractWithdrawalRequest::class)->find($id);
    }

    public function getWithdrawalRequestByUuid(string $uuid): ?ContractWithdrawalRequest
    {
        return $this->em->getRepository(ContractWithdrawalRequest::class)->findOneBy(['uuid' => $uuid]);
    }

    public function getWithdrawalRequestsByContract(Contract $contract): array
    {
        return $this->em->getRepository(ContractWithdrawalRequest::class)->findWithdrawalRequestsByContract($contract);
    }

    public function getWithdrawalRequestsByUser(User $user): array
    {
        return $this->em->getRepository(ContractWithdrawalRequest::class)->findWithdrawalRequestsByUser($user);
    }

    public function getTotalsAmountByApprovedWithdrawalsAndContract(Contract $contract): int|float
    {
        $total = $this->em->getRepository(ContractWithdrawalRequest::class)->sumApprovedWithdrawalsAmountByContract($contract);
        return $total ?? 0;
    }
}
