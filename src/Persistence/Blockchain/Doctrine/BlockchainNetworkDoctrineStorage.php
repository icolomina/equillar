<?php

namespace App\Persistence\Blockchain\Doctrine;

use App\Entity\BlockchainNetwork;
use App\Persistence\Blockchain\BlockchainNetworkStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class BlockchainNetworkDoctrineStorage extends AbstractDoctrineStorage implements BlockchainNetworkStorageInterface
{
    public function getByBlockchainAndNetwork(string $blockchain, string $network): ?BlockchainNetwork
    {
        return $this->em->getRepository(BlockchainNetwork::class)->findByBlockchainAndNetwork($blockchain, $network);
    }
}
