<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\SystemWallet;

use App\Domain\Crypt\CryptedValue;

readonly class SystemWalletData
{
    public function __construct(
        public string $address,
        public string $blockchain,
        public string $network,
        public string $url,
        public bool $isTest,
        public CryptedValue $cryptedValue,
    ) {
    }
}
