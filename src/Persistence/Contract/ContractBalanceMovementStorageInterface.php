<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\Contract;

use App\Entity\User;
use App\Entity\Contract\ContractBalanceMovement;

interface ContractBalanceMovementStorageInterface
{
    /**
     * @return ContractBalanceMovement[]
     */
    public function getByUser(User $user): array;

    /**
     * @return ContractBalanceMovement[]
     */
    public function getAll(): array;
}
