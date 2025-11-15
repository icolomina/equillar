<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
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
