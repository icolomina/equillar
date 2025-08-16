<?php

namespace App\Domain\Contract;

enum ContractEvent: string
{
    case ContractBalanceUpdated = 'CBUPDATED';
}
