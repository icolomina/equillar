<?php

namespace App\Command;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\DeployContractService;
use App\Persistence\Token\TokenStorageInterface;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\Investment\InitializeTokenOperation;
use Soneso\StellarSDK\Crypto\StrKey;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:token:generate'
)]
class GenerateTokenContractCommand extends Command
{
    public function __construct(
        private readonly DeployContractService $deployContractService,
        private readonly InitializeTokenOperation $initializeTokenOperation,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly string $tokenName,
        private readonly string $tokenSymbol,
        private readonly int $tokenDecimals,
        private readonly string $tokenIssuer
    ){
        parent::__construct();
    }

    public function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tokenWasmId = $this->deployContractService->deployTokenContract();
        $output->writeln('Token deployed: ' . $tokenWasmId);

        $tokenContractId = $this->initializeTokenOperation->initializeTokenContract($tokenWasmId, $this->tokenName, $this->tokenSymbol, $this->tokenDecimals);
        $output->writeln('Token contract installed: ' . $tokenContractId);

        $token = $this->tokenStorage->createToken(
            StrKey::encodeContractIdHex($tokenContractId),
            $this->tokenName,
            $this->tokenSymbol,
            $this->tokenDecimals,
            $this->tokenIssuer
        );

        $output->writeln('Token saved on the database: ' . $token->getName() . ' - ' . $token->getCode());
        return Command::SUCCESS;
    }
}
