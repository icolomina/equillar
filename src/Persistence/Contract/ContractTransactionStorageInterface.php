<?php

namespace App\Persistence\Contract;

use App\Entity\ContractTransaction;

interface ContractTransactionStorageInterface
{
    public function saveContractTransaction(ContractTransaction $contractTransaction): void;
}
