<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
