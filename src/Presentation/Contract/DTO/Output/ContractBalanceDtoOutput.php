<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Presentation\Contract\DTO\Output;


readonly class ContractBalanceDtoOutput
{
    public function __construct(
        public float $available,
        public float $reserveFund,
        public float $commision,
        public float $fundsReceived,
        public float $payments,
        public float $projectWithdrawals,
        public float $reserveFundContributions,
        public float $percentajeFundsReceived,
        public float $availableToReserveMovements
    ) {
    }
}
