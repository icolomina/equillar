<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\Contract;

enum ContractMultisigStatus: int
{
    case WAITING_FOR_SIGNATURES = 1;
    case COMPLETED = 2;
}
