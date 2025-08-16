<?php

namespace App\Command\Contract\Investment;

use App\Application\UserContract\Service\PayUserContractService;
use App\Application\UserContract\Transformer\UserContractPaymentEntityTransformer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Persistence\UserContract\UserContractStorageInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Contract\UserContract;
use App\Persistence\PersistorInterface;

#[AsCommand(
    name: 'app:contract:execute-payments'
)]
class ExecutePaymentsCommand extends Command
{
    public function __construct(
        private readonly UserContractStorageInterface $userContractStorage,
        private readonly PayUserContractService $payUserContractService,
        private readonly UserContractPaymentEntityTransformer $userContractPaymentEntityTransformer,
        private readonly PersistorInterface $persistor
    ){
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addOption('claimable-from', null, InputOption::VALUE_REQUIRED, 'Claimable from', date('Y-m-d H:i:s'))
            ->addOption('last-payment-from', null, InputOption::VALUE_REQUIRED, 'Last payment from', date('Y-m-d H:i:s', strtotime('-1 month')))
        ;

    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $claimableFrom = new \DateTimeImmutable($input->getOption('claimable-from'));
        $lastPaymentFrom = new \DateTimeImmutable($input->getOption('last-payment-from'));

        /**
         * @var UserContract[] $candidateUserContracts
         */
        $candidateUserContracts = $this->userContractStorage->getClaimableCandidates($claimableFrom, $lastPaymentFrom);
        foreach($candidateUserContracts as $candidateUserContract) {
            $userContractPayment = $this->userContractPaymentEntityTransformer->createFromPayableUserContract($candidateUserContract);
            $io->writeln('Ready to tranfer payment to user: ' . $userContractPayment->getUserContract()->getUsr()->getUserIdentifier() . ' Contract: ' . $userContractPayment->getUserContract()->getContract()->getLabel());
            $this->persistor->persistAndFlush($userContractPayment);
            $this->payUserContractService->payUserContract($userContractPayment);

            $io->writeln(sprintf('Processed payment: Status: (%s) - Result: (%s)', $userContractPayment->getStatus(), $userContractPayment->getTransaction()->getTrxResult()));
        }

        return Command::SUCCESS;
    }
}
