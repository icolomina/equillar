<?php

namespace App\Application\SystemWallet\Service;

use App\Domain\Crypt\CryptedValue;
use App\Domain\SystemWallet\SystemWalletData;
use App\Persistence\SystemWallet\SystemWalletStorageInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;

class RetrieveSystemWalletService
{
    public function __construct(
        private readonly SystemWalletStorageInterface $systemWalletStorage,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function retrieve(): SystemWalletData
    {
        $cache = new FilesystemAdapter();

        return $cache->get('system.default_wallet', function (ItemInterface $item): SystemWalletData {
            $item->expiresAfter(3600);
            $defaultWallet = $this->systemWalletStorage->getDefaultWallet();

            /**
             * @var CryptedValue $cryptedValue
             */
            $cryptedValue = $this->serializer->denormalize($defaultWallet->getPrivateKey(), CryptedValue::class);

            return new SystemWalletData(
                $defaultWallet->getAddress(),
                $defaultWallet->getBlockchainNetwork()->getBlockchain()->getName(),
                $defaultWallet->getBlockchainNetwork()->getLabel(),
                $defaultWallet->getBlockchainNetwork()->getUrl(),
                $defaultWallet->getBlockchainNetwork()->isTest(),
                $cryptedValue
            );
        });
    }
}
