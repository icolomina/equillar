<?php

namespace App\Domain\Contract;

enum ContractPaymentAvailabilityStatus
{
    case PENDING;
    case PROCESSED;
    case FAILED;
}
