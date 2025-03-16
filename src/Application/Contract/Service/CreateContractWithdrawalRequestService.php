<?php

namespace App\Application\Investment\Contract\Service;

use App\Application\Investment\Contract\Transformer\ContractInvestmentWithdrawalRequestEntityTransformer;
use App\Entity\Investment\ContractInvestment;
use App\Message\CheckContractInvestmentWithdrawalRequestMessage;
use App\Persistence\Investment\Contract\ContractInvestmentStorageInterface;
use App\Persistence\PersistorInterface;
use App\Presentation\Contract\DTO\Input\ContractInvestmentRequestWithdrawalDtoInput;
use App\Presentation\Contract\DTO\Output\ContractInvestmentWithdrawalRequestDtoOutput;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateContractWithdrawalRequestService
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly ContractInvestmentStorageInterface $contractInvestmentStorageInterface,
        private readonly ContractInvestmentWithdrawalRequestEntityTransformer $contractInvestmentWithdrawalRequestEntityTransformer,
        private readonly PersistorInterface $persistorInterface
    ){}

    public function createContractWithdrawalRequest(ContractInvestment $contract, ContractInvestmentRequestWithdrawalDtoInput $contractRequestWithdrawalDtoInput): ContractInvestmentWithdrawalRequestDtoOutput
    {
        $requestWithdrawal  = $this->contractInvestmentWithdrawalRequestEntityTransformer->fromRequestWithdrawalDtoToEntity(
            $contract, 
            $contractRequestWithdrawalDtoInput
        );

        $this->persistorInterface->persistAndFlush($requestWithdrawal);
        $this->bus->dispatch(new CheckContractInvestmentWithdrawalRequestMessage($requestWithdrawal->getId()));

        return $this->contractInvestmentWithdrawalRequestEntityTransformer->fromEntityToOutputDto($requestWithdrawal);
    }
}
