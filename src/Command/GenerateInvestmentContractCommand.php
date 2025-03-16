<?php

namespace App\Command;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\DeployContractService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name : 'app:contract:deploy'
)]
class GenerateInvestmentContractCommand extends Command
{
    public function __construct(
        private readonly DeployContractService $deployContractService
    ){
        parent::__construct();
    }

    public function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Deploying and installing token contract ....');
        $wasmId = $this->deployContractService->deployInvestmentContract();
        $output->writeln('Deployed contract wasm ID: ' . $wasmId);

        return Command::SUCCESS;
    }
}