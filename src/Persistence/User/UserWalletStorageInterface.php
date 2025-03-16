<?php

namespace App\Persistence\User;

use App\Entity\UserWallet;

interface UserWalletStorageInterface 
{
    public function getWalletByAddress(string $address): ?UserWallet;
}
