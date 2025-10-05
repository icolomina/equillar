<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Command\Contract\User;

use App\Application\UserContract\Service\ProcessUserContractService;
use App\Persistence\UserContract\UserContractStorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:contract:user:check-trx'
)]
class CheckUserContractCommand extends Command
{
    public function __construct(
        private readonly UserContractStorageInterface $userContractStorage,
        private readonly ProcessUserContractService $processUserContractService,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addOption('user_contract_id', null, InputOption::VALUE_REQUIRED, 'User Contract Id')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $userContractId = $input->getOption('user_contract_id');
        $userContract = $this->userContractStorage->getById($userContractId);
        $this->processUserContractService->processUserContractTransaction($userContract);

        return Command::SUCCESS;
    }
}
