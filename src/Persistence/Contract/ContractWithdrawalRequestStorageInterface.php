<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Persistence\Contract;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Entity\User;

interface ContractWithdrawalRequestStorageInterface
{
    public function getWithdrawalRequestById(int $id): ?ContractWithdrawalRequest;

    public function getWithdrawalRequestByUuid(string $uuid): ?ContractWithdrawalRequest;

    public function getWithdrawalRequestsByContract(Contract $contract): array;

    public function getWithdrawalRequestsByUser(User $user): array;

    public function getAllWithdrawalRequests(): array;

    public function getTotalsAmountByApprovedWithdrawalsAndContract(Contract $contract): int|float|null;
}
