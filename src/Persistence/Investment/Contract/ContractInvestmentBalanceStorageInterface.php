<?php

namespace App\Persistence\Investment\Contract;

use App\Entity\Investment\ContractInvestment;
use App\Entity\Investment\ContractInvestmentBalance;

interface ContractInvestmentBalanceStorageInterface
{
    public function getBalanceByContractInvestment(ContractInvestment $contractInvestment): array;
    public function getLastBalanceByContractInvestment(ContractInvestment $contractInvestment): ?ContractInvestmentBalance;
    public function getLastSuccesfulBalanceByContractInvestment(ContractInvestment $contractInvestment): ?ContractInvestmentBalance;
}
