<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\Contract;

enum ContractReturnType: int
{
    case REVERSE_LOAN = 1;
    case COUPON = 2;

    public function getReadableName(): string
    {
        return match ($this) {
            self::REVERSE_LOAN => 'Reverse loan',
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
