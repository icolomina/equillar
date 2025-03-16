<?php

namespace App\Domain\Contract;

enum ContractReturnType: int
{
    case REVERSE_LOAN = 1;
    case COUPON = 2;
    case ONE_TIME_PAYMENT = 3;
}
