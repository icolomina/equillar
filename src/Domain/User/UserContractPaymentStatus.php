<?php

namespace App\Domain\User;

enum UserContractPaymentStatus
{
    case SENT;
    case CONFIRMED;
}
