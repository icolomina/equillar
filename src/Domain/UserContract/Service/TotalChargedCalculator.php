<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\UserContract\Service;

class TotalChargedCalculator
{
    public function calculateTotalCharged(?float $currentTotalCharged, float $amountClaimed): float
    {
        $currentTotalCharged = $currentTotalCharged ?? 0;

        return $amountClaimed + $currentTotalCharged;
    }
}
