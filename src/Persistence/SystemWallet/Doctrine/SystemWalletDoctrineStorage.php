<?php

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
