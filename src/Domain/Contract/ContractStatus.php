<?php

namespace App\Domain\Contract;

enum ContractStatus
{
    case REVIEWING;
    case APPROVED;
    case ACTIVE;
    case FUNDS_REACHED;
    case FINISHED;
}
