<?php

namespace App\Domain\Contract;

enum ContractWithdrawalStatus
{
    case REQUESTED;
    case CONFIRMED;
    case FAILED;
    case FUNDS_SENT;
}
