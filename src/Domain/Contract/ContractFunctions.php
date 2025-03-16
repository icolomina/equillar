<?php

namespace App\Domain\Contract;

enum ContractFunctions
{
    case init;
    case invest;
    case claim;
    case mint;
    case balance;
    case decimals;
    case get_contract_balance;
    case stop_investments;
    case project_withdrawn;
    case check_project_address_balance;
}
