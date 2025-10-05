<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\Utils\Math;

use App\Domain\I128;

class I128Handler
{
    public function fromIntToI128(int $number): I128
    {
        return new I128($number);
    }

    public function fromI128ToPhpFloat(?int $lo, ?int $hi, int $decimals): float
    {
        $value = $lo ?? $hi;

        return $value / pow(10, $decimals);
    }
}
