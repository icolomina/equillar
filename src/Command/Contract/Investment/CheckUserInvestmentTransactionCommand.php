<?php

namespace App\Command\Contract\Investment;

use App\Application\UserContract\Service\ProcessUserInvestmentTransactionService;
use App\Persistence\Investment\UserContract\UserContractInvestmentStorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:contract:investment:check-trx'
)]
class CheckUserInvestmentTransactionCommand extends Command
{
    public function __construct(
        private readonly UserContractInvestmentStorageInterface $userContractInvestmentStorage,
        private readonly ProcessUserInvestmentTransactionService $processUserInvestmentTransactionService
    ){
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addOption('user_investment_id', null, InputOption::VALUE_REQUIRED, 'User Investment Id')
        ;

    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $userInvestmentId = $input->getOption('user_investment_id');
        $userInvestment   = $this->userContractInvestmentStorage->getById($userInvestmentId);
        $this->processUserInvestmentTransactionService->processUserInvestmentTransaction($userInvestment);
        
        return Command::SUCCESS;

    }
}
