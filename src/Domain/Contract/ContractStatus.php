<?php

namespace App\Domain\Contract;

enum ContractStatus
{
    case REVIEWING;
    case APPROVED;
    case REJECTED;
    case ACTIVE;
    case FUNDS_REACHED;
    case FINISHED;
    case DEPLOYMENT_FAILED;
    case BLOCKED;
}
