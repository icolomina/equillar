<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain;

class I128
{
    private int $lo;
    private int $hi;

    public function __construct(?int $number = null, ?int $lo = null, ?int $hi = null)
    {
        if ($number !== null) {
            // Verify we're on a 64-bit system
            if (PHP_INT_SIZE !== 8) {
                throw new \RuntimeException('I128 requires 64-bit PHP (PHP_INT_SIZE must be 8, got ' . PHP_INT_SIZE . ')');
            }
            
            // I128 in Stellar uses 64-bit parts, not 32-bit
            // For positive numbers that fit in 64 bits, hi is 0 and lo contains the full value
            // lo is unsigned 64-bit, hi is signed 64-bit
            if ($number < 0) {
                throw new \InvalidArgumentException('Negative numbers not yet supported in I128');
            }
            
            $this->lo = $number;
            $this->hi = 0;
        } else {
            $this->lo = $lo;
            $this->hi = $hi;
        }
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
        // For I128 with 64-bit parts, if hi is 0, just return lo
        if ($this->hi === 0) {
            return $this->lo;
        }
        // If hi is not 0, we would need to handle it, but for values
        // that fit in 64 bits (which is our case), hi should always be 0
        return (int)($this->hi * pow(2, 64)) + $this->lo;
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
