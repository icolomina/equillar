<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\UserContract\Service;

class TotalChargedCalculator
{
    public function calculateTotalCharged(?float $currentTotalCharged, float $amountClaimed): float
    {
        $currentTotalCharged = $currentTotalCharged ?? 0;

        return $amountClaimed + $currentTotalCharged;
    }
}
