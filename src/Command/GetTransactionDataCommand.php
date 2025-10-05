<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
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
        private readonly GetStellarTransactionDataService $getBlockchainTransactionDataService,
    ) {
    }

    public function __invoke(SymfonyStyle $io, #[Argument] string $txHash): int
    {
        $transactionData = $this->getBlockchainTransactionDataService->getTransactionData($txHash);

        $successfulResultText = ($transactionData->isSuccessful) ? 'Yes' : 'No';

        $io->writeln('Successful: '.$successfulResultText);
        $io->writeln('Hash: '.$transactionData->hash);
        $io->writeln('Ledger: '.$transactionData->ledger);
        $io->writeln('Fee Charged: '.$transactionData->feeCharged);

        return Command::SUCCESS;
    }
}
