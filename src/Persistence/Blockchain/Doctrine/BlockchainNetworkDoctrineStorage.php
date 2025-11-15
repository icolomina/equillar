<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
