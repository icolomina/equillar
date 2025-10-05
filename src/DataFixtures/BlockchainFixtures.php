<?php

namespace App\DataFixtures;

use App\Entity\Blockchain;
use App\Entity\BlockchainNetwork;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlockchainFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $blockchain = new Blockchain();
        $blockchain->setName('Stellar');
        $blockchain->setLabel('stellar');
        $blockchain->setInfoUrl('https://stellar.org');
        $blockchain->setCreatedAt(new \DateTimeImmutable());

        $blockchainNetwork = new BlockchainNetwork();
        $blockchainNetwork->setBlockchain($blockchain);
        $blockchainNetwork->setLabel('testnet');
        $blockchainNetwork->setName('Testnet');
        $blockchainNetwork->setUrl('https://soroban-testnet.stellar.org');
        $blockchainNetwork->setCreatedAt(new \DateTimeImmutable());
        $blockchainNetwork->setTest(true);

        $manager->persist($blockchain);
        $manager->persist($blockchainNetwork);
        $manager->flush();
    }
}
