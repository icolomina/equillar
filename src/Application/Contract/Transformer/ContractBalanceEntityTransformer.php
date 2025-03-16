<?php

namespace App\Application\Contract\Transformer;

use App\Entity\Investment\ContractInvestment;
use App\Entity\Investment\ContractInvestmentBalance;
use App\Presentation\Contract\DTO\Output\ContractInvestmentBalanceDtoOutput;

class ContractBalanceEntityTransformer
{
    public function fromContractInvestmentToBalance(ContractInvestment $contractInvestment): ContractInvestmentBalance
    {
        $contractInvestmentBalance = new ContractInvestmentBalance();
        $contractInvestmentBalance->setContractInvestment($contractInvestment);
        $contractInvestmentBalance->setCreatedAt(new \DateTimeImmutable());

        return $contractInvestmentBalance;
    }

    public function fromEntityToOutputDto(ContractInvestmentBalance $contractInvestmentBalance, bool $showCommission)
    {
        $commision = ($showCommission) ? $contractInvestmentBalance->getComission() : null;
        return new ContractInvestmentBalanceDtoOutput(
            $contractInvestmentBalance->getAvailable(),
            $contractInvestmentBalance->getReserveFund(),
            $commision
        );
    }
}
