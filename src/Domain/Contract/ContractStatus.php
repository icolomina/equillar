<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\Contract;

enum ContractStatus
{
    case REVIEWING;
    case APPROVED;
    case REJECTED;
    case ACTIVE;
    case PAUSED;
    case FUNDS_REACHED;
    case FINISHED;
    case DEPLOYMENT_FAILED;
    case BLOCKED;
}
