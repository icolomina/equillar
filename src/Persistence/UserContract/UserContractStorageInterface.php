<?php

namespace App\Persistence\UserContract;

use App\Entity\Contract\Contract;
use App\Entity\Contract\UserContract;
use App\Entity\User;

interface UserContractStorageInterface
{
    public function getById(int $id): ?UserContract;

    /**
     * @return UserContract[]
     */
    public function getByUser(User $user): array;
    public function getByUserAndContract(User $user, Contract $contract): ?UserContract;
    public function getPorfolioUserContracts(User $user): array;
    public function saveUserContract(UserContract $userContract): void;
    public function getClaimableCandidates(\DateTimeImmutable $claimableFrom, \DateTimeImmutable $lastPaymentFrom): iterable;
}
