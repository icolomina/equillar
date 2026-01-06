<?php

namespace App\Message\Handler;

use App\Application\Contract\Service\Blockchain\ContractCheckPaymentAvailabilityService;
use App\Domain\Contract\Exception\ContractExecutionFailedException;
use App\Message\CheckContractPaymentAvailabilityMessage;
use App\Persistence\Contract\ContractPaymentAvailabilityStorageInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckContractPaymentAvailabilityMessageHandler
{
    public function __construct(
        private readonly ContractPaymentAvailabilityStorageInterface $contractPaymentAvailabilityStorage,
        private readonly ContractCheckPaymentAvailabilityService $contractCheckPaymentAvailabilityService
    ){
    }

    public function __invoke(CheckContractPaymentAvailabilityMessage $message): void
    {
        $contractPaymentAvailability = $this->contractPaymentAvailabilityStorage->getById($message->contractPaymentAvailabilityId);
        try{
            $this->contractCheckPaymentAvailabilityService->checkContractAvailability($contractPaymentAvailability);
        }
        catch(ContractExecutionFailedException $e){}
        
    }
}