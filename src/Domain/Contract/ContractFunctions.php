<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
    case check_reserve_balance;
    case add_company_transfer;
    case move_funds_to_the_reserve;

    /**
     * TokenInterface contract functions.
     */
    case decimals;
    case mint;
    case balance;
}
