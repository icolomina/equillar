<?php

namespace App\Command\Contract\Muxed;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Domain\Contract\Service\ContractMuxedIdGenerator;
use App\Persistence\Contract\ContractStorageInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name : 'app:contract:generate-muxed-account'
)]
class GenerateMuxedAccountCommand
{
    public function __construct(
        private readonly ContractStorageInterface $contractStorage,
        private readonly ContractMuxedIdGenerator $contractMuxedIdGenerator,
        private readonly StellarAccountLoader $stellarAccountLoader
    ){}

    public function __invoke(SymfonyStyle $io, #[Argument] string $cid): int
    {
        if(empty($cid)) {
            $io->writeln('Provide a contract ID as an argument');
            return Command::INVALID;
        }

        $contract = $this->contractStorage->getContractById((int)$cid);
        $muxedId  = $this->contractMuxedIdGenerator->generateMuxedId($contract);
        $muxedAddress = $this->stellarAccountLoader->generateMuxedAccount($muxedId);

        $io->writeln('Muxed Address: ' . $muxedAddress);
        $io->writeln('Muxed ID: ' . $muxedId);

        return Command::SUCCESS;
    }
}
