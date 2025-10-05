<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Command;

use App\Application\Contract\Service\DeployContractService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name : 'app:contract:deploy'
)]
class GenerateContractCommand
{
    public function __construct(
        private readonly DeployContractService $deployContractService,
        private readonly string $wasmFile,
    ) {
    }

    public function __invoke(SymfonyStyle $io, #[Option] string $status = 'STABLE', #[Option] string $vers = '1.0', #[Option] ?string $comments = null): int
    {
        $io->writeln('Deploying and installing investment contract ....');

        $wasmId = $this->deployContractService->deployContract($this->wasmFile,
            $status,
            $vers,
            $comments
        );

        $io->writeln('Deployed contract wasm ID: '.$wasmId);

        return Command::SUCCESS;
    }
}
