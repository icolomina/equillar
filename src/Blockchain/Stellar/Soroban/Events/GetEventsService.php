<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Blockchain\Stellar\Soroban\Events;

use App\Blockchain\Stellar\Exception\Transaction\EventsRequestTransactionException;
use App\Blockchain\Stellar\Soroban\Server\ServerLoaderService;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\Crypto\StrKey;
use Soneso\StellarSDK\Soroban\Requests\EventFilter;
use Soneso\StellarSDK\Soroban\Requests\EventFilters;
use Soneso\StellarSDK\Soroban\Requests\GetEventsRequest;
use Soneso\StellarSDK\Soroban\Requests\TopicFilter;
use Soneso\StellarSDK\Soroban\Requests\TopicFilters;
use Soneso\StellarSDK\Soroban\Responses\GetEventsResponse;
use Soneso\StellarSDK\Soroban\SorobanServer;
use Soneso\StellarSDK\Xdr\XdrSCVal;
use Symfony\Component\DependencyInjection\Attribute\Lazy;

#[Lazy]
class GetEventsService
{
    private SorobanServer $server;

    public function __construct(
        private readonly ServerLoaderService $serverLoaderService,
    ) {
        $this->server = $this->serverLoaderService->getServer();
    }

    /**
     * @param string[] $topics
     */
    public function getContractEvents(Contract $contract, array $topics, ?int $startLedger = null): GetEventsResponse
    {
        $contractAddress = StrKey::encodeContractIdHex($contract->getAddress());
        $segmentMatchers = [];

        foreach ($topics as $topic) {
            $segmentMatchers[] = ('*' === $topic)
                ? $topic
                : XdrSCVal::forSymbol($topic)->toBase64Xdr()
            ;
        }

        $topicFilters = new TopicFilters(new TopicFilter($segmentMatchers));
        $eventFilter = new EventFilter('contract', [$contractAddress], $topicFilters);
        $eventFilters = new EventFilters();
        $eventFilters->add($eventFilter);

        $request = new GetEventsRequest(
            startLedger: $startLedger,
            filters: $eventFilters,
        );

        $response = $this->server->getEvents($request);
        if ($response->getError()) {
            throw new EventsRequestTransactionException($response->getError()->getMessage());
        }

        return $response;
    }
}
