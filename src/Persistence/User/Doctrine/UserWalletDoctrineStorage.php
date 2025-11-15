<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\User\Doctrine;

use App\Entity\UserWallet;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;
use App\Persistence\User\UserWalletStorageInterface;

class UserWalletDoctrineStorage extends AbstractDoctrineStorage implements UserWalletStorageInterface
{
    public function getWalletByAddress(string $address): ?UserWallet
    {
        return $this->em->getRepository(UserWallet::class)->findOneByAddress($address);
    }
}
