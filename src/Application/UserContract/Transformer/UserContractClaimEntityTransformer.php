<?php

namespace App\Application\UserContract\Transformer;

use App\Entity\Investment\UserContractInvestmentClaim;

class UserContractClaimEntityTransformer
{
    public function updateInvestmentClaimWithSuccessfulTransactionResult(UserContractInvestmentClaim $userContractInvestmentClaim, string $trxHash, float $totalClaimed, \DateTimeImmutable $claimedAt): void
    {
        $userContractInvestmentClaim->setClaimedAt($claimedAt);
        $userContractInvestmentClaim->setTotalClaimed($totalClaimed);
        $userContractInvestmentClaim->setHash($trxHash);
        $userContractInvestmentClaim->setStatus('SUCCESS');
    }
}
