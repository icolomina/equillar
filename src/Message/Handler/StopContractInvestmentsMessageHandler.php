<?php

namespace App\Message\Handler;

use App\Application\Contract\Service\StopInvestmentsService;
use App\Message\StopContractInvestmentsMessage;
use App\Persistence\Contract\ContractStorageInterface;

class StopContractInvestmentsMessageHandler
{
    public function __construct(
        private readonly ContractStorageInterface $contractStorage,
        private readonly StopInvestmentsService $stopInvestmentsService
    ){}

    public function __invoke(StopContractInvestmentsMessage $stopContractInvestmentsMessage): void
    {
        $contract = $this->contractStorage->getContractById($stopContractInvestmentsMessage->contractId);
        $this->stopInvestmentsService->stopInvestments($contract, $stopContractInvestmentsMessage->reason);
    }
}
