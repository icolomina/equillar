<?php

namespace App\Domain\Contract;

enum ContractReserveFundContributionStatus
{
    case CREATED;
    case RECEIVED;
    case TRANSFERRED;
    case FAILED;
}
