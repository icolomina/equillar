<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
