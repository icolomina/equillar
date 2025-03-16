<?php

namespace App\Application\Contract\Transformer;

use App\Entity\ContractTransaction;
use App\Entity\Investment\ContractInvestmentWithdrawalApprovement;
use App\Entity\Investment\ContractInvestmentWithdrawalRequest;

class ContractWithdrawalApprovementEntityTransformer
{
    public function fromRequestApprovedToEntity(ContractInvestmentWithdrawalRequest $contractInvestmentWithdrawalRequest, ContractTransaction $contractTransaction, string $status): ContractInvestmentWithdrawalApprovement
    {
        $approvement = new ContractInvestmentWithdrawalApprovement();
        $approvement->setContractInvestmentWithdrawalRequest($contractInvestmentWithdrawalRequest);
        $approvement->setApprovedAt(new \DateTimeImmutable());
        $approvement->setStatus($status);
        $approvement->setContractTransaction($contractTransaction);

        return $approvement;
    }

    public function fromRequestApprovementFailureToEntity(ContractInvestmentWithdrawalRequest $contractInvestmentWithdrawalRequest, ContractTransaction $contractTransaction, string $status): ContractInvestmentWithdrawalApprovement
    {
        $approvement = new ContractInvestmentWithdrawalApprovement();
        $approvement->setContractInvestmentWithdrawalRequest($contractInvestmentWithdrawalRequest);
        $approvement->setStatus($status);
        $approvement->setContractTransaction($contractTransaction);

        return $approvement;
    }
}
