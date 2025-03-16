<?php

namespace App\Application\UserContract\Mutator;

use App\Domain\UserContract\Service\TotalChargedCalculator;
use App\Entity\Investment\UserContractInvestment;

class UserContractInvestmentMutator
{

    public function __construct(
        private readonly TotalChargedCalculator $totalChargedCalculator
    ){}

    public function updateUserContractInvestmentWithNewClaim(UserContractInvestment $userContractInvestment, \DateTimeImmutable $transferredAt): void
    {
        $currentTotalCharged = $userContractInvestment->getTotalCharged() ?? 0;

        $userContractInvestment->setLastPaymentReceivedAt($transferredAt);
        $userContractInvestment->setTotalCharged($this->totalChargedCalculator->calculateTotalCharged($currentTotalCharged, $userContractInvestment->getRegularPayment()) + $currentTotalCharged);
    }
}
