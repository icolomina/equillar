<?php

namespace App\Domain\UserContract;

enum UserContractStatus: int
{
    case BLOCKED = 1; 
    case CLAIMABLE = 2;
    case WAITING_FOR_PAYMENT = 3;
    case CASH_FLOWING = 4;
    case FINISHED = 5;
    case UNKNOWN = 6;
}
