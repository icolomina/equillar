<?php

namespace App\Message\Handler;

use App\Application\Contract\Service\Blockchain\ContractWithdrawalApprovalService;
use App\Message\ApproveRequestWithdrawalMessage;
use App\Persistence\Contract\ContractWithdrawalRequestStorageInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ApproveRequestWithdrawalMessageHandler
{
    public function __construct(
        private readonly ContractWithdrawalRequestStorageInterface $contractWithdrawalRequestStorage,
        private readonly ContractWithdrawalApprovalService $contractWithdrawalApprovalService
    ){}

    public function __invoke(ApproveRequestWithdrawalMessage $approveRequestWithdrawalMessage): void
    {
        $contractWithdrawalRequest = $this->contractWithdrawalRequestStorage->getWithdrawalRequestById($approveRequestWithdrawalMessage->requestWithdrawalId);
        $this->contractWithdrawalApprovalService->processProjectWithdrawal($contractWithdrawalRequest);
    }
}
