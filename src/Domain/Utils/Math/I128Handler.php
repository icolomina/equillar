<?php

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
