<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Command\Contract;

use App\Application\Contract\Service\Blockchain\ContractReserveFundContributionsProcessorService;
use App\Domain\Contract\ContractProcessIncomingContributionsResult;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name : 'app:contract:reserve-fund-contributions:check-payments'
)]
class ContractCheckReserveFundContributionsCommand
{
    public function __construct(
        private readonly ContractReserveFundContributionsProcessorService $contractReserveFundContributionsProcessorService
    ) {}

    public function __invoke(SymfonyStyle $io)
    {
        $contributionsProcessed = $this->contractReserveFundContributionsProcessorService->processIncomingContributons();
        foreach ($contributionsProcessed as $paymentTrx => $result) {
            $io->writeln('Result for contribution ' . $paymentTrx);
            match($result->status) {
                ContractProcessIncomingContributionsResult::INVALID_TRANSACTION => $io->writeln('Invalid Transaction'),
                ContractProcessIncomingContributionsResult::EMPTY_MUXED_DESTINATION_ACCOUNT => $io->writeln('Muxed destination account empty'),
                ContractProcessIncomingContributionsResult::SOURCE_ACCOUNT_AND_PROJECT_ADDRESS_NOT_MATCH => $io->writeln('Source account and project address does not match'),
                ContractProcessIncomingContributionsResult::CONTRIBUTION_ALREADY_PROCESSED => $io->writeln('Contributon already processed'),
                ContractProcessIncomingContributionsResult::SYSTEM_ADDRESS_DOES_NOT_HOLD_ENOUGHT_TOKEN_BALANCE => $io->writeln('System address does not hold enoght balance for contract token'),
                default => $io->writeln('Contribution payment processed')
            };

            $io->writeln('---------------------------------------------');
        } 

        return Command::SUCCESS;
    }
}
