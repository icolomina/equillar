<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Persistence\Contract;


use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractReserveFundContribution;
use App\Entity\User;

interface ContractReserveFundContributionStorageInterface
{
    public function getByUuid(string $uuid): ?ContractReserveFundContribution;

    public function getByUuidAndStatus(string $uuid, string $status): ?ContractReserveFundContribution;

    public function getTotalContributionsByContract(Contract $contract): int|float|null;

    /**
     * @return ContractReserveFundContribution[]
     */
    public function getByContract(Contract $contract): array;

    public function getByPaymentTransactionHash(string $paymentTransactionHash): ?ContractReserveFundContribution;

    /**
     * @return ContractReserveFundContribution[]
     */
    public function getByUser(User $user): array;

    /**
     * @return ContractReserveFundContribution[]
     */
    public function getAll(): array;
}
