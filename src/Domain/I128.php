<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain;

class I128
{
    private int $lo;
    private int $hi;

    public function __construct(?int $number = null, ?int $lo = null, ?int $hi = null)
    {
        $this->lo = ($number) ? $number & 0xFFFFFFFF : $lo;
        $this->hi = ($number) ? ($number >> 32) & 0xFFFFFFFF : $hi;
    }

    public function getLo(): int
    {
        return $this->lo;
    }

    public function getHi(): int
    {
        return $this->hi;
    }

    public function reverse(): int|string
    {
        return ($this->hi * pow(2, 32)) + $this->lo;
    }

    public function toPhp(int $decimals): int|float
    {
        $reversed = $this->reverse();

        return $reversed / pow(10, $decimals);
    }

    public static function fromLoAndHi(int $lo, int $hi): self
    {
        return new self(null, $lo, $hi);
    }
}
