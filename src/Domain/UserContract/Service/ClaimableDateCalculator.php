<?php

namespace App\Domain\UserContract\Service;

class ClaimableDateCalculator
{
    public function getClaimableDate(?int $days, ?int $months): string
    {
        if(!$days && !$months) {
            throw new \LogicException();
        }

        return ($months) 
            ? (new \DateTime())->add(\DateInterval::createFromDateString("+ {$months} months"))->format('Y-m-d')
            : (new \DateTime())->add(\DateInterval::createFromDateString("+ {$days} days"))->format('Y-m-d')
        ;
    }

    public function getDaysToClaim(int $claimMonths): int
    {
        $nowDt         = new \DateTimeImmutable();
        $monthsAfterDt = $nowDt->add(\DateInterval::createFromDateString("+ {$claimMonths} months"));
        return $monthsAfterDt->diff($nowDt)->days;
    }
}
