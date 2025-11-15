<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
