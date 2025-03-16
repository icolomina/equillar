<?php

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
