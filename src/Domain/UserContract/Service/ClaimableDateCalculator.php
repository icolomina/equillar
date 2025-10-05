<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\UserContract\Service;

class ClaimableDateCalculator
{
    public function getClaimableDate(?int $days, ?int $months): string
    {
        if (!$days && !$months) {
            return (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        }

        return ($months)
            ? (new \DateTime())->add(\DateInterval::createFromDateString("+ {$months} months"))->format('Y-m-d H:i:s')
            : (new \DateTime())->add(\DateInterval::createFromDateString("+ {$days} days"))->format('Y-m-d H:i:s')
        ;
    }

    public function getDaysToClaim(int $claimMonths): int
    {
        $nowDt = new \DateTimeImmutable();
        $monthsAfterDt = $nowDt->add(\DateInterval::createFromDateString("+ {$claimMonths} months"));

        return $monthsAfterDt->diff($nowDt)->days;
    }
}
