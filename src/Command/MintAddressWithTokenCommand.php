<?php

namespace App\Command;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Token\MintTokenOperation;
use App\Persistence\Token\TokenStorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:token:mint'
)]
class MintAddressWithTokenCommand extends Command
{
    public function __construct(
        private readonly MintTokenOperation $mintTokenOperation,
        private readonly TokenStorageInterface $tokenStorage
    ){
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->addOption('amount', null, InputOption::VALUE_REQUIRED, 'Amount to mint with')
            ->addOption('token', null, InputOption::VALUE_REQUIRED, 'Token Symbol')
            ->addOption('address', null, InputOption::VALUE_REQUIRED, 'Address to mint')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $this->tokenStorage->getOneByCode($input->getOption('token'));
        $addressToMint = $input->getOption('address');
        $amount = $input->getOption('amount');

        $output->writeln('Minting address: ' . $addressToMint . ' with ' . $amount . ' tokens' );
        $this->mintTokenOperation->mintToken($token, $addressToMint, $amount);
        $output->writeln('Address ' . $addressToMint . ' minted successfully' );
        return Command::SUCCESS;
    }
}
