<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Persistence\Contract;


use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractBalance;

interface ContractBalanceStorageInterface
{
    public function getBalanceByContract(Contract $contract): array;

    public function getLastBalanceByContract(Contract $contract): ?ContractBalance;

    public function getLastSuccesfulBalanceByContract(Contract $contract): ?ContractBalance;
}
