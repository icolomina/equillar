<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Command;

use Soneso\StellarSDK\Crypto\StrKey;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name : 'app:contract:encode',
    description: 'Encode a contract id'
)]
class EncodeContractCommand extends Command
{
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
    }

    public function configure(): void
    {
        $this
            ->addArgument('address', InputArgument::REQUIRED, 'The address to encode / decode')
            ->addOption('decode', null, InputOption::VALUE_NONE, 'Decode Address ?')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $value = ($input->getOption('decode'))
            ? StrKey::decodeContractIdHex($input->getArgument('address'))
            : StrKey::encodeContractIdHex($input->getArgument('address'))
        ;

        $output->writeln('Result: '.$value);

        return Command::SUCCESS;
    }
}
