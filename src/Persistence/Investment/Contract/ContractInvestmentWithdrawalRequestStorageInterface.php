<?php

namespace App\Persistence\Investment\Contract;

use App\Entity\Investment\ContractInvestmentWithdrawalRequest;

interface ContractInvestmentWithdrawalRequestStorageInterface
{
    public function getWithdrawalRequestById(int $id): ?ContractInvestmentWithdrawalRequest;
}
