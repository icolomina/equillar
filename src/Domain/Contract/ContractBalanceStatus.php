<?php

namespace App\Domain\Contract;

enum ContractBalanceStatus
{
    case CONFIRMED;
    case FAILED;
}
