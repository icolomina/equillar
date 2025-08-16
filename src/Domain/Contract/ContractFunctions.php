<?php

namespace App\Domain\Contract;

enum ContractFunctions
{
    /**
     * This is not really a function. It means the contract deployment which executes the contract constructor function
     */
    case activation;

    /**
     * Invest contract functions
     */
    case invest;
    case process_investor_payment;
    case get_contract_balance;
    case stop_investments;
    case single_withdrawn;
    case check_reserve;
    case add_company_transfer;

    /**
     * TokenInterface contract functions
     */
    case decimals;
    case mint;
    case balance;
}
