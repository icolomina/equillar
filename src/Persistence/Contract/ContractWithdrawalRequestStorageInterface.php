<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
