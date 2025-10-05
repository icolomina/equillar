<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Persistence\UserContract;

use App\Entity\Contract\Contract;
use App\Entity\Contract\UserContract;
use App\Entity\Contract\UserContractPayment;
use App\Entity\User;

interface UserContractPaymentStorageInterface
{
    public function getById(string $id): ?UserContractPayment;

    public function getByUser(User $user): array;

    public function getTotalPaidByContract(Contract $contract): int|float|null;

    /**
     * @return UserContractPayment[]
     */
    public function getTransferredPaymentsByUserContract(UserContract $userContract): array;
}
