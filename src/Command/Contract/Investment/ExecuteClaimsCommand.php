<?php

namespace App\Command\Contract\Investment;

use App\Application\Contract\Service\ClaimInvestmentService;
use App\Persistence\Investment\UserContract\UserContractInvestmentStorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Investment\UserContractInvestment;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:contract:investment:execute-claims'
)]
class ExecuteClaimsCommand extends Command
{
    public function __construct(
        private readonly UserContractInvestmentStorageInterface $userContractInvestmentStorage,
        private readonly ClaimInvestmentService $claimInvestmentService
    ){
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addOption('clamaible-from', null, InputOption::VALUE_REQUIRED, 'Claimable from', date('Y-m-d H:i:s'))
            ->addOption('last-payment-from', null, InputOption::VALUE_REQUIRED, 'Last payment from', date('Y-m-d H:i:s', strtotime('-1 month')))
        ;

    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $claimableFrom = new \DateTimeImmutable($input->getOption('claimable-from'));
        $lastPaymentFrom = new \DateTimeImmutable($input->getOption('last-payment-from'));

        /**
         * @var UserContractInvestment[] $candidateClaims
         */
        $candidateClaims = $this->userContractInvestmentStorage->getClaimableCandidates($claimableFrom, $lastPaymentFrom);
        foreach($candidateClaims as $candidateClaim) {
            //$result = $this->claimInvestmentService->claimInvestment($candidateClaim);

        }

        return Command::SUCCESS;
    }
}
