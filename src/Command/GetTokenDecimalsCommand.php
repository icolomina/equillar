<?php

namespace App\Command;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Token\GetTokenDecimalsOperation;
use App\Domain\Token\Service\TokenNormalizer;
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
        private readonly GetTokenDecimalsOperation $getTokenDecimalsOperation
    ){
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

        $token    = $this->tokenStorage->getOneByCode($input->getArgument('token'));
        $decimals = $this->getTokenDecimalsOperation->getTokenDecimals($token);

        $output->writeln(sprintf('Decimals for token %s: %s', $token->getCode(), $decimals ));

        return Command::SUCCESS;
    }
}
