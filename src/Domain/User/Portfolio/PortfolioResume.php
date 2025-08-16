<?php

namespace App\Domain\User\Portfolio;

class PortfolioResume
{
    public function __construct(
        public array $depositInfo,
        public array $interestsInfo,
        public array $totalInfo,
        public array $totalChargedInfo,
        public array $totalPendingToChargeInfo,
        public array $totalClaimableInfo,
    ) {}

    
}
