<?php

namespace App\Persistence\Contract;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractBalance;

interface ContractBalanceStorageInterface
{
    public function getBalanceByContract(Contract $contract): array;
    public function getLastBalanceByContract(Contract $contract): ?ContractBalance;
    public function getLastSuccesfulBalanceByContract(Contract $contract): ?ContractBalance;
}
