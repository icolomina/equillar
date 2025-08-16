<?php

namespace App\Persistence\Blockchain;

use App\Entity\BlockchainNetwork;

interface BlockchainNetworkStorageInterface
{
    public function getByBlockchainAndNetwork(string $blockchain, string $network): ?BlockchainNetwork;
}
