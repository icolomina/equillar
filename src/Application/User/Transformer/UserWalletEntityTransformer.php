<?php

namespace App\Application\User\Transformer;

use App\Application\SystemWallet\Service\RetrieveSystemWalletService;
use App\Entity\User;
use App\Entity\UserWallet;

class UserWalletEntityTransformer
{
    public function __construct(
        private readonly RetrieveSystemWalletService $retrieveSystemWalletService
    ){}

    public function fromUserAndAddressToUserWalletEntity(User $user, string $address): UserWallet
    {
        $systemWalletData = $this->retrieveSystemWalletService->retrieve();

        $userWallet = new UserWallet();
        $userWallet->setUsr($user);
        $userWallet->setAddress($address);
        $userWallet->setCreatedAt(new \DateTimeImmutable());
        $userWallet->setNetwork($systemWalletData->blockchain . ' -- ' . $systemWalletData->network);

        return $userWallet;
    }
}
