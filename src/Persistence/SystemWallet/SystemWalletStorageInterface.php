<?php

namespace App\Persistence\SystemWallet;

use App\Entity\SystemWallet;

interface SystemWalletStorageInterface
{
    public function getDefaultWallet(): SystemWallet;
}
