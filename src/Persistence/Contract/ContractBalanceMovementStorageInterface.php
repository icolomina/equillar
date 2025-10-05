<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
