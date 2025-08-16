<?php

namespace App\Persistence\UserContract;

use App\Entity\Contract\Contract;
use App\Entity\Contract\UserContract;
use App\Entity\Contract\UserContractPayment;
use App\Entity\User;

interface UserContractPaymentStorageInterface
{
    public function getById(string $id): ?UserContractPayment;
    public function getByUser(User $user): array;

    public function getTotalPaidByContract(Contract $contract): int|float|null ;

    /**
     * @return UserContractPayment[]
     */
    public function getTransferredPaymentsByUserContract(UserContract $userContract): array ;
}
