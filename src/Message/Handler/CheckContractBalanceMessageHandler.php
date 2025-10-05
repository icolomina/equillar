<?php

namespace App\Message\Handler;

use App\Application\Contract\Service\Blockchain\ContractBalanceGetAndUpdateService;
use App\Application\Contract\Service\Blockchain\Event\ContractBalanceGetAndUpdateFromEventsService;
use App\Message\CheckContractBalanceMessage;
use App\Persistence\Contract\ContractStorageInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckContractBalanceMessageHandler
{
    public function __construct(
        private readonly ContractStorageInterface $contractStorage,
        private readonly ContractBalanceGetAndUpdateFromEventsService $contractBalanceGetAndUpdateFromEventsService,
        private readonly ContractBalanceGetAndUpdateService $contractBalanceGetAndUpdateService,
    ) {
    }

    public function __invoke(CheckContractBalanceMessage $checkContractBalanceMessage): void
    {
        $contract = $this->contractStorage->getContractById($checkContractBalanceMessage->contractId);
        ($checkContractBalanceMessage->startLedger)
            ? $this->contractBalanceGetAndUpdateFromEventsService->getContractBalanceEvents($contract, $checkContractBalanceMessage->startLedger)
            : $this->contractBalanceGetAndUpdateService->getContractBalance($contract)
        ;
    }
}
