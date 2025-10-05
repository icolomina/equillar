<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Persistence\SystemWallet\Doctrine;

use App\Entity\SystemWallet;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;
use App\Persistence\SystemWallet\SystemWalletStorageInterface;

class SystemWalletDoctrineStorage extends AbstractDoctrineStorage implements SystemWalletStorageInterface
{
    public function getDefaultWallet(): SystemWallet
    {
        return $this->em->getRepository(SystemWallet::class)->findOneBy(['defaultWallet' => true]);
    }
}
