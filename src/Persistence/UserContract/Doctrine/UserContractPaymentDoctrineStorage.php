<?php

namespace App\Persistence\UserContract\Doctrine;

use App\Entity\Contract\Contract;
use App\Entity\Contract\UserContract;
use App\Entity\Contract\UserContractPayment;
use App\Entity\User;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;
use App\Persistence\UserContract\UserContractPaymentStorageInterface;

class UserContractPaymentDoctrineStorage extends AbstractDoctrineStorage implements UserContractPaymentStorageInterface
{
    public function getById(string $id): ?UserContractPayment
    {
        return $this->em->getRepository(UserContractPayment::class)->findOneBy(['id' => $id]);
    }

    public function getByUser(User $user): array
    {
        return $this->em->getRepository(UserContractPayment::class)->findUserContractPayments($user);
    }

    public function getTotalPaidByContract(Contract $contract): int|float
    {
        $total = $this->em->getRepository(UserContractPayment::class)->sumUserContractPaymentsByContract($contract);
        return $total ?? 0;
    }

    /**
     * @return UserContractPayment[]
     */
    public function getTransferredPaymentsByUserContract(UserContract $userContract): array
    {
        return $this->em->getRepository(UserContractPayment::class)->findBy(['userContract' => $userContract, 'status' => 'CONFIRMED']);
    }
}
