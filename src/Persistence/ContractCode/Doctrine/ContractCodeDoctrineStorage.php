<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\ContractCode\Doctrine;

use App\Entity\ContractCode;
use App\Persistence\ContractCode\ContractCodeStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class ContractCodeDoctrineStorage extends AbstractDoctrineStorage implements ContractCodeStorageInterface
{
    public function getLastdeployedContractCode(): ?ContractCode
    {
        return $this->em->getRepository(ContractCode::class)->findOneBy(
            ['status' => 'STABLE'],
            ['id' => 'desc']
        );
    }
}
