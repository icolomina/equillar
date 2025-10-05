<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Command\Contract\Payment;

use App\Application\UserContract\Service\PayUserContractService;
use App\Persistence\UserContract\UserContractPaymentStorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:contract:process-payment'
)]
class ProcessUserContractPaymentTransactionCommand extends Command
{
    public function __construct(
        private readonly UserContractPaymentStorageInterface $userContractPaymentStorage,
        private readonly PayUserContractService $payUserContractService,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addOption('ucp_id', null, InputOption::VALUE_REQUIRED, 'User contract payment id')
            ->addOption('hash', null, InputOption::VALUE_REQUIRED, 'Trx Hash')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $id = $input->getOption('ucp_id');

        $userContractPayment = $this->userContractPaymentStorage->getById($id);
        $this->payUserContractService->payUserContract($userContractPayment, true);
        $io->writeln(sprintf('Processed payment: Status: (%s) - Result: (%s)', $userContractPayment->getStatus(), $userContractPayment->getTransaction()->getTrxResult()));

        return Command::SUCCESS;
    }
}
