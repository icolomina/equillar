<?php

namespace App\Message\Handler;

use App\Application\UserContract\Service\ProcessUserContractService;
use App\Message\CheckUserContractMessage;
use App\Persistence\UserContract\UserContractStorageInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckUserContractMessageHandler
{
    public function __construct(
        private readonly UserContractStorageInterface $userContractStorage,
        private readonly ProcessUserContractService $processUserContractTransactionService
    ){}

    public function __invoke(CheckUserContractMessage $checkUserTransactionMessage): void
    {
        $userContract = $this->userContractStorage->getById($checkUserTransactionMessage->userInvestmentId);
        $this->processUserContractTransactionService->processUserContractTransaction($userContract);
    }
}
