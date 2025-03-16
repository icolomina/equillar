<?php

namespace App\Command;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Token\GetBalanceOperation;
use App\Persistence\Token\TokenStorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:token:balance'
)]
class GetAddressBalanceCommand extends Command
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly GetBalanceOperation $getBalanceOperation
    ){
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->addOption('token', null, InputOption::VALUE_REQUIRED, 'Token')
            ->addOption('address', null, InputOption::VALUE_REQUIRED, 'Address')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $this->tokenStorage->getOneByCode($input->getOption('token'));
        $balance = $this->getBalanceOperation->getTokenBalance($token, $input->getOption('address'));

        dump($balance);
        return Command::SUCCESS;
    }
}
