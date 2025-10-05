<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
