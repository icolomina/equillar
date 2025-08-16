<?php

namespace App\Command;

use App\Blockchain\Stellar\Transaction\GetStellarTransactionDataService;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:transaction'
)]
class GetTransactionDataCommand
{
    public function __construct(
        private readonly GetStellarTransactionDataService $getBlockchainTransactionDataService
    ){}

    public function __invoke(SymfonyStyle $io, #[Argument] string $txHash): int
    {
        $transactionData = $this->getBlockchainTransactionDataService->getTransactionData($txHash);
        
        $successfulResultText = ($transactionData->isSuccessful) ? 'Yes' : 'No';

        $io->writeln('Successful: ' . $successfulResultText);
        $io->writeln('Hash: ' . $transactionData->hash);
        $io->writeln('Ledger: ' . $transactionData->ledger);
        $io->writeln('Fee Charged: ' . $transactionData->feeCharged);

        return Command::SUCCESS;
    }
}
