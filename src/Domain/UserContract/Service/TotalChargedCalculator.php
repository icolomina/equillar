<?php

namespace App\Domain\UserContract\Service;

class TotalChargedCalculator
{
    public function calculateTotalCharged(?float $currentTotalCharged, float $amountClaimed): float
    {
        $currentTotalCharged = $currentTotalCharged ?? 0;
        return $amountClaimed + $currentTotalCharged;
    }
}
