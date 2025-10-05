<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
