<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
