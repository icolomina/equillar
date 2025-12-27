<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Command;

use App\Application\SystemWallet\Service\CreateEncryptedSystemWalletService;
use App\Persistence\Blockchain\BlockchainNetworkStorageInterface;
use Soneso\StellarSDK\Crypto\KeyPair;
use Soneso\StellarSDK\Util\FriendBot;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name : 'app:generate-system-wallet'
)]
class GenerateSystemWalletCommand
{
    public function __construct(
        private readonly BlockchainNetworkStorageInterface $blockchainNetworkStorage,
        private readonly KernelInterface $kernel,
        private readonly CreateEncryptedSystemWalletService $createSystemWalletService
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Option] ?string $network = null,
        #[Option] ?string $blockchain = null,
        #[Option] ?string $secret = null,
    ): int {
        $blockchainNetwork = $this->blockchainNetworkStorage->getByBlockchainAndNetwork($blockchain, $network);

        if (!$blockchainNetwork) {
            $io->warning(sprintf('There is no blockchain network matching values: %s - %s', $blockchain, $network));

            return Command::INVALID;
        }

        if (!$blockchainNetwork->isTest() && 'prod' !== $this->kernel->getEnvironment()) {
            $io->warning('You cannot create a public address on a non production environment');

            return Command::INVALID;
        }

        $io->writeln('Generating key-pair ...');
        $keyPair = (!empty($secret))
            ? KeyPair::fromSeed($secret)
            : KeyPair::random()
        ;

        if ($blockchainNetwork->isTest()) {
            $io->writeln('Funding address with friendbot ....');
            $funded = FriendBot::fundTestAccount($keyPair->getAccountId());
            if (!$funded) {
                $io->error('Unable to fund address with XLM');

                return Command::FAILURE;
            }

            $io->writeln('Address funded successfully');
        }

        $io->writeln('Encrypting address secret ....');
        $systemWallet = $this->createSystemWalletService->create(
            $blockchainNetwork,
            $keyPair->getAccountId(),
            $keyPair->getSecretSeed()
        );

        $io->success('Done !');

        return Command::SUCCESS;
    }
}
