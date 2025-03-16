<?php

namespace App\Persistence\Investment\UserContract;

use App\Entity\Investment\UserContractInvestment;
use App\Entity\User;

interface UserContractInvestmentStorageInterface
{
    public function getById(int $id): ?UserContractInvestment;
    public function getByUser(User $user): array;
    public function saveUserContract(UserContractInvestment $userContractInvestment): void;
    public function getClaimableCandidates(\DateTimeImmutable $claimableFrom, \DateTimeImmutable $lastPaymentFrom): iterable;
}
