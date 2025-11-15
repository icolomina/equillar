<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
