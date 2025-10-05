<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
