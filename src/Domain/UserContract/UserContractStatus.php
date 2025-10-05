<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\UserContract;

enum UserContractStatus: int
{
    case BLOCKED = 1;
    case CLAIMABLE = 2;
    case WAITING_FOR_PAYMENT = 3;
    case CASH_FLOWING = 4;
    case FINISHED = 5;
    case UNKNOWN = 6;
}
