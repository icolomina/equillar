<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\UserContract;

class UserContractPaymentCalendarItem
{
    public function __construct(
        public readonly string $date,
        public readonly float $value,
        public bool $isTransferred = false,
        public ?string $transferredAt = null,
        public ?string $willBeTransferredAt = null,
    ) {
    }
}
