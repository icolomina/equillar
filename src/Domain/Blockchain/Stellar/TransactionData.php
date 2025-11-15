<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Blockchain\Stellar;

class TransactionData
{
    public function __construct(
        public bool $isSuccessful,
        public int $ledger,
        public string $feeCharged,
        public string $hash,
    ) {
    }
}
