<?php

namespace App\Command\Contract\Investment;

use App\Application\Contract\Service\GetContractBalanceService;
use App\Persistence\Investment\Contract\ContractInvestmentBalanceStorageInterface;
use App\Persistence\Investment\Contract\ContractInvestmentStorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:contract:investment:check-balance'
)]
class CheckContractInvestmentBalanceCommand extends Command
{
    public function __construct(
        private readonly ContractInvestmentStorageInterface $contractInvestmentStorage,
        private readonly GetContractBalanceService $getContractBalanceService,
        private readonly ContractInvestmentBalanceStorageInterface $contractInvestmentBalanceStorage
    ){
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addOption('cid', null, InputOption::VALUE_OPTIONAL, 'Contract Investment Id')
        ;

    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $cid = $input->getOption('cid');
        if($cid) {
            $contractInvestment = $this->contractInvestmentStorage->getContractById($cid);
            $contracts = ($contractInvestment) ? [$contractInvestment] : [];
        }
        else {
            $contracts = $this->contractInvestmentStorage->getInitializedContracts();
        }

        if(empty($contracts)) {
            $io->writeln('There are no contracts to check');
            return Command::SUCCESS;
        }

        $this->getContractBalanceService->getContractBalance($contractInvestment);
        $lastCheckedBalance = $this->contractInvestmentBalanceStorage->getLastBalanceByContractInvestment($contractInvestment);

        return Command::SUCCESS;
    }
}
