<?php

namespace App\Domain\Contract;

enum ContractMultisigStatus: int
{
    case WAITING_FOR_SIGNATURES = 1;
    case COMPLETED = 2; 
}
