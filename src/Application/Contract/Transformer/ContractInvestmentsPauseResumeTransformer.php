<?php

namespace App\Application\Contract\Transformer;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractInvestmentsPauseResume;
use App\Entity\ContractTransaction;

class ContractInvestmentsPauseResumeTransformer
{
    public function fromContractSuccessfulPausedOrResumedInvestments(Contract $contract, ContractTransaction $contractTransaction, float $currentFunds, string $reason, string $type): ContractInvestmentsPauseResume
    {
        $contractInvestmentsPauseResume = $this->fromContractPausedOrResumedInvestments($contract, $contractTransaction, $currentFunds, $reason, $type);
        $contractInvestmentsPauseResume->setStatus('SUCCESS');

        return $contractInvestmentsPauseResume;
    }

    public function fromContractFailurePausedOrResumedInvestments(Contract $contract, ContractTransaction $contractTransaction, float $currentFunds, string $reason, string $type): ContractInvestmentsPauseResume
    {
        $contractInvestmentsPauseResume = $this->fromContractPausedOrResumedInvestments($contract, $contractTransaction, $currentFunds, $reason, $type);
        $contractInvestmentsPauseResume->setStatus('FAILED');

        return $contractInvestmentsPauseResume;
    }

    private function fromContractPausedOrResumedInvestments(Contract $contract, ContractTransaction $contractTransaction, float $currentFunds, string $reason, string $type): ContractInvestmentsPauseResume
    {
        $contractInvestmentsPauseResume = new ContractInvestmentsPauseResume();
        $contractInvestmentsPauseResume->setContract($contract);
        $contractInvestmentsPauseResume->setDate(new \DateTimeImmutable());
        $contractInvestmentsPauseResume->setCurrentFunds($currentFunds);
        $contractInvestmentsPauseResume->setReason($reason);
        $contractInvestmentsPauseResume->setType($type);
        $contractInvestmentsPauseResume->setContractTransaction($contractTransaction);

        return $contractInvestmentsPauseResume;   
    }
}
