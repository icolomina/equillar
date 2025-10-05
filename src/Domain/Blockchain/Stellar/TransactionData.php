<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
