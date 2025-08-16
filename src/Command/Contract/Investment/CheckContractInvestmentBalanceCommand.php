<?php

namespace App\Command\Contract\Investment;

use App\Application\Contract\Service\Blockchain\ContractBalanceGetAndUpdateService;
use App\Application\Contract\Service\Blockchain\Event\ContractBalanceGetAndUpdateFromEventsService;
use App\Application\Contract\Service\Blockchain\Event\GetContractBalanceUpdatedEventsService;
use App\Persistence\Contract\ContractStorageInterface;
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
        private readonly ContractStorageInterface $contractStorage,
        private readonly ContractBalanceGetAndUpdateService $getContractBalanceService,
        private readonly ContractBalanceGetAndUpdateFromEventsService $contractBalanceGetAndUpdateFromEventsService
    ){
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addOption('cid', null, InputOption::VALUE_REQUIRED, 'Contract Investment Id')
            ->addOption('type', null, InputOption::VALUE_OPTIONAL, 'Checking type')
            ->addOption('ledger', null, InputOption::VALUE_OPTIONAL, 'Start ledger')
        ;

    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io   = new SymfonyStyle($input, $output);
        $cid  = $input->getOption('cid');
        $type = $input->getOption('type');

        $contract = $this->contractStorage->getContractById($cid);
        ($type === 'RPC')
            ? $this->getContractBalanceService->getContractBalance($contract)
            : $this->contractBalanceGetAndUpdateFromEventsService->getContractBalanceEvents($contract, $input->getOption('ledger'))
        ;

        $io->writeln('Contract Balance updated');
        return Command::SUCCESS;
    }
}
