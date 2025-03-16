<?php

namespace App\Message\Handler;

use App\Application\UserContract\Service\ProcessUserInvestmentTransactionService;
use App\Message\CheckUserInvestmentTransactionMessage;
use App\Persistence\Investment\UserContract\UserContractInvestmentStorageInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckUserInvestmentTransactionMessageHandler
{
    public function __construct(
        private readonly UserContractInvestmentStorageInterface $userContractInvestmentStorage,
        private readonly ProcessUserInvestmentTransactionService $processUserInvestmentTransactionService
    ){}

    public function __invoke(CheckUserInvestmentTransactionMessage $checkUserInvestmentTransactionMessage): void
    {
        $userInvestment = $this->userContractInvestmentStorage->getById($checkUserInvestmentTransactionMessage->userInvestmentId);
        $this->processUserInvestmentTransactionService->processUserInvestmentTransaction($userInvestment);
    }
}
