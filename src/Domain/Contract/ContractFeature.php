<?php

namespace App\Domain\Contract;

enum ContractFeature
{
    case MANUAL_WITHDRAWN;
    case REVERSE_LOAN_PAYMENT;
}
