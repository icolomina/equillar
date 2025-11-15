<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
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
