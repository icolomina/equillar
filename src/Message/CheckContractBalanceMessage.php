<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Message;

readonly class CheckContractBalanceMessage
{
    public function __construct(
        public string|int $contractId,
        public ?int $startLedger = null,
    ) {
    }
}
