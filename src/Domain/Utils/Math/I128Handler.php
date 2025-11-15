<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
