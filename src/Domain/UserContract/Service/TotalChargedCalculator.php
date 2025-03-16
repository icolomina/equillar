<?php

namespace App\Domain\UserContract\Service;

class TotalChargedCalculator
{
    public function calculateTotalCharged(?float $currentTotalCharged, string $amountClaimed): float
    {
        $currentTotalCharged = $currentTotalCharged ?? 0;
        return (float)$amountClaimed + $currentTotalCharged;
    }
}
