<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Persistence\Contract;


use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractBalance;

interface ContractBalanceStorageInterface
{
    public function getBalanceByContract(Contract $contract): array;

    public function getLastBalanceByContract(Contract $contract): ?ContractBalance;

    public function getLastSuccesfulBalanceByContract(Contract $contract): ?ContractBalance;
}
