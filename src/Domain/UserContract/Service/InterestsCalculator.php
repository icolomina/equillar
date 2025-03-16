<?php

namespace App\Domain\UserContract\Service;

class InterestsCalculator
{
    public function calculateInterest(float $balance, int $rate, int $decimals): float
    {
        return round( ($balance * ($rate / 100)), $decimals);   
    }
}
