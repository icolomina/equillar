<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Contract;

enum ContractReturnType: int
{
    case REVERSE_LOAN = 1;
    case COUPON = 2;

    public function getReadableName(): string
    {
        return match ($this) {
            self::REVERSE_LOAN => 'Reverse Loan',
            default => 'Coupon',
        };
    }

    /**
     * @return int[]
     */
    public static function getValues(): array
    {
        return [
            self::COUPON->value,
            self::REVERSE_LOAN->value,
        ];
    }
}
