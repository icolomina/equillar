<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
