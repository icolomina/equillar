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
        private readonly GetTokenDecimalsOperation $getTokenDecimalsOperation,
        private readonly TokenNormalizer $tokenNormalizer
    ){
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->addArgument('token', InputArgument::REQUIRED, 'Token')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $i128 = $this->tokenNormalizer->normalizeTokenValue(623952.14365, 7);

        $output->writeln('parte baja: ' . $i128->getLo());
        $output->writeln('parte alta: ' . $i128->getHi());
        $output->writeln('Valor original: ' . $i128->reverse());
        $output->writeln('Valor original nativo: ' . $i128->toPhp(7));

        return Command::SUCCESS;

        $token    = $this->tokenStorage->getOneByCode($input->getArgument('token'));
        $decimals = $this->getTokenDecimalsOperation->getTokenDecimals($token);

        return Command::SUCCESS;
    }
}
