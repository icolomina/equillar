<?php

namespace App\Command;

use App\Application\SystemWallet\Transformer\SystemWalletEntityTransformer;
use App\Domain\Crypt\Service\Encryptor;
use App\Persistence\Blockchain\BlockchainNetworkStorageInterface;
use App\Persistence\PersistorInterface;
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
        private readonly Encryptor $encryptor,
        private readonly BlockchainNetworkStorageInterface $blockchainNetworkStorage,
        private readonly KernelInterface $kernel,
        private readonly SystemWalletEntityTransformer $systemWalletEntityTransformer,
        private readonly PersistorInterface $persistor
    ){}

    public function __invoke(
        SymfonyStyle $io,
        #[Option] ?string $network = null,
        #[Option] ?string $blockchain = null,
        #[Option] ?string $secret = null,
    ): int
    {
        $blockchainNetwork = $this->blockchainNetworkStorage->getByBlockchainAndNetwork($blockchain, $network);

        if(!$blockchainNetwork) {
            $io->warning(sprintf('There is no blockchain network matching values: %s - %s', $blockchain, $network));
            return Command::INVALID;
        }

        if(!$blockchainNetwork->isTest() && $this->kernel->getEnvironment() !== 'prod') {
            $io->warning('You cannot create a public address on a non production environment');
            return Command::INVALID;   
        }

        $io->writeln('Generating key-pair ...');
        $keyPair = (!empty($secret))
            ? KeyPair::fromSeed($secret)
            : KeyPair::random()
        ;

        if($blockchainNetwork->isTest()){
            $io->writeln('Funding address with friendbot ....');
            $funded = FriendBot::fundTestAccount($keyPair->getAccountId());
            if(!$funded) {
                $io->error('Unable to fund address with XLM');
                return Command::FAILURE;
            }

            $io->writeln('Address funded successfully');
        }   

        $io->writeln('Encrypting address secret ....');
        $cryptedValue = $this->encryptor->encryptMsg($keyPair->getSecretSeed());
        $systemWallet = $this->systemWalletEntityTransformer->fromBlockchainNetworkAndCrypedValueToEntity($blockchainNetwork, $cryptedValue, $keyPair->getAccountId());

        $io->writeln('Checking encryption before persisting');
        $decrytedString = $this->encryptor->decryptMsg($cryptedValue->cipher, $cryptedValue->nonce);
        if($decrytedString !== $keyPair->getSecretSeed()) {
            $io->error('Invalid secret encryption ...');
            return Command::FAILURE;
        }

        $io->writeln('Persisting wallet data...');
        $this->persistor->persistAndFlush($systemWallet);

        $io->success('Done !');
        return Command::SUCCESS;

    }
}
