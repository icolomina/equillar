<?php

namespace App\Message\Handler;

use App\Application\Contract\Service\ProcessWithdrawalRequestTransactionService;
use App\Message\CheckContractInvestmentWithdrawalRequestMessage;
use App\Persistence\Investment\Contract\ContractInvestmentWithdrawalRequestStorageInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckContractInvestmentWithdrawalRequestMessageHandler
{
    public function __construct(
        private readonly ContractInvestmentWithdrawalRequestStorageInterface $contractInvestmentWithdrawalRequestStorage,
        private readonly ProcessWithdrawalRequestTransactionService $processWithdrawalRequestTransactionService
    ){}

    public function __invoke(CheckContractInvestmentWithdrawalRequestMessage $checkContractInvestmentWithdrawalRequestMessage): void
    {
        $requestWithdrawal = $this->contractInvestmentWithdrawalRequestStorage->getWithdrawalRequestById($checkContractInvestmentWithdrawalRequestMessage->requestWithdrawalId);
        $this->processWithdrawalRequestTransactionService->processWithdrawalRequest($requestWithdrawal);
    }
}
