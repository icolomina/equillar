<?php

namespace App\Domain\Contract;

enum ContractReturnType: int
{
    case REVERSE_LOAN = 1;
    case COUPON = 2;

    public function getReadableName(): string
    {
        return match($this) {
            self::REVERSE_LOAN => 'Reverse loan',
            default => 'Coupon'
        };
    }

    public static function getValues(): array
    {
        return [
            self::COUPON->value,
            self::REVERSE_LOAN->value
        ];
    }
}
