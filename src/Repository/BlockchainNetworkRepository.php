<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Repository;

use App\Entity\BlockchainNetwork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlockchainNetwork>
 */
class BlockchainNetworkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockchainNetwork::class);
    }

    public function findByBlockchainAndNetwork(string $blockchain, string $network): ?BlockchainNetwork
    {
        $qb = $this->createQueryBuilder('bn');

        return $qb
            ->innerJoin('bn.blockchain', 'b')
            ->andWhere($qb->expr()->eq('b.label', ':blockchain_label'))
            ->andWhere($qb->expr()->eq('bn.label', ':blockchain_network_label'))
            ->setParameter('blockchain_label', $blockchain)
            ->setParameter('blockchain_network_label', $network)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
