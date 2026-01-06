<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Command\Contract;

use App\Application\Contract\Transformer\ContractPaymentAvailabilityTransformer;
use App\Domain\Contract\ContractStatus;
use App\Message\CheckContractPaymentAvailabilityMessage;
use App\Persistence\Contract\ContractStorageInterface;
use App\Persistence\PersistorInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:contract:check-payment-availability'
)]
class CheckContractPaymentAvailabilityCommand extends Command
{
    public function __construct(
        private readonly ContractStorageInterface $contractStorage,
        private readonly ContractPaymentAvailabilityTransformer $contractPaymentAvailabilityTransformer,
        private readonly PersistorInterface $persistor,
        private readonly MessageBusInterface $bus
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $statuses = [
            ContractStatus::ACTIVE,
            ContractStatus::FUNDS_REACHED,
            ContractStatus::PAUSED,
        ];

        $contracts = $this->contractStorage->getContractsByStatuses($statuses);
        
        $io->writeln(sprintf('Found %d contracts to check', count($contracts)));

        foreach ($contracts as $contract) {
            $io->writeln(sprintf('Checking contract: %s - %s', $contract->getId(), $contract->getLabel()));
            
            $contractPaymentAvailability = $this->contractPaymentAvailabilityTransformer->fromContractToPaymentAvailability($contract);
            $this->persistor->persistAndFlush($contractPaymentAvailability);
                
            $this->bus->dispatch(new CheckContractPaymentAvailabilityMessage($contractPaymentAvailability->getId()));
                
            $io->success(sprintf('Contract %s queued successfully', $contract->getLabel()));
        }

        return Command::SUCCESS;
    }
}
