<?php

namespace App\Application\Contract\Transformer;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractInvestmentsPauseResume;
use App\Entity\ContractTransaction;

class ContractInvestmentsPauseResumeTransformer
{
    public function fromContractStoppedInvestments(Contract $contract, ContractTransaction $contractTransaction, float $currentFunds, string $reason): ContractInvestmentsPauseResume
    {
        $contractInvestmentsPauseResume = new ContractInvestmentsPauseResume();
        $contractInvestmentsPauseResume->setContract($contract) ;
        $contractInvestmentsPauseResume->setDate(new \DateTimeImmutable());
        $contractInvestmentsPauseResume->setCurrentFunds($currentFunds);
        $contractInvestmentsPauseResume->setReason($reason);
        $contractInvestmentsPauseResume->setType('STOP');
        $contractInvestmentsPauseResume->setContractTransaction($contractTransaction);

        return $contractInvestmentsPauseResume;

    }
}
