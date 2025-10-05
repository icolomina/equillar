<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\Contract;

enum ContractFunctions
{
    /**
     * This is not really a function. It means the contract deployment which executes the contract constructor function.
     */
    case activation;

    /**
     * Invest contract functions.
     */
    case invest;
    case process_investor_payment;
    case get_contract_balance;
    case stop_investments;
    case restart_investments;
    case single_withdrawn;
    case check_reserve;
    case add_company_transfer;
    case move_funds_to_the_reserve;

    /**
     * TokenInterface contract functions.
     */
    case decimals;
    case mint;
    case balance;
}
