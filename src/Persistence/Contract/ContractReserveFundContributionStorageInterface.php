<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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

    /**
     * @return ContractReserveFundContribution[]
     */
    public function getByUser(User $user): array;

    /**
     * @return ContractReserveFundContribution[]
     */
    public function getAll(): array;
}
