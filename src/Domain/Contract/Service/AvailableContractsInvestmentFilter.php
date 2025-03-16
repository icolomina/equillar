<?php

namespace App\Domain\Contract\Service;

use App\Entity\Investment\ContractInvestment;
use App\Entity\Investment\UserContractInvestment;

class AvailableContractsInvestmentFilter
{
    /**
     * @param UserContractInvestment[] $userContracts
     */
    public function isAvailableContractInvestment(ContractInvestment $c, array $userContracts): bool
    {
        $contractsUser = array_filter($userContracts, fn(UserContractInvestment $uc) => $uc->getContract()->getId() === $c->getId());
        return empty($contractsUser);
    }
}
