<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Soroban\ScContract\Event;

use App\Blockchain\Stellar\Soroban\Events\GetEventsService;
use App\Domain\Contract\ContractEvent;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\Soroban\Responses\GetEventsResponse;

class GetContractBalanceUpdatedEvents
{
    public function __construct(
        private readonly GetEventsService $getEventsService,
    ) {
    }

    public function getContractBalanceUpdatedEvents(Contract $contract, ?int $startLedger = null): GetEventsResponse
    {
        return $this->getEventsService->getContractEvents(
            $contract,
            [ContractEvent::ContractBalanceUpdated->value],
            $startLedger
        );
    }
}
