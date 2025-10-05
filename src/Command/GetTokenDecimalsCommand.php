<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Command;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Token\GetTokenDecimalsOperation;
use App\Persistence\Token\TokenStorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:token:decimals'
)]
class GetTokenDecimalsCommand extends Command
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly GetTokenDecimalsOperation $getTokenDecimalsOperation,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addArgument('token', InputArgument::REQUIRED, 'Token')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $this->tokenStorage->getOneByCode($input->getArgument('token'));
        $decimals = $this->getTokenDecimalsOperation->getTokenDecimals($token);

        $output->writeln(sprintf('Decimals for token %s: %s', $token->getCode(), $decimals));

        return Command::SUCCESS;
    }
}
