<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
    ) {
    }
}
