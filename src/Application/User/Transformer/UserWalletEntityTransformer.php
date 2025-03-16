<?php

namespace App\Application\User\Transformer;

use App\Entity\User;
use App\Entity\UserWallet;
use App\Stellar\Networks;

class UserWalletEntityTransformer
{
    public function fromUserAndAddressToUserWalletEntity(User $user, string $address): UserWallet
    {
        $userWallet = new UserWallet();
        $userWallet->setUsr($user);
        $userWallet->setAddress($address);
        $userWallet->setCreatedAt(new \DateTimeImmutable());
        $userWallet->setNetwork(Networks::TESTNET->value);

        return $userWallet;
    }
}
