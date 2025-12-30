<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\User\Transformer;

use App\Application\SystemWallet\Service\RetrieveSystemWalletService;
use App\Entity\User;
use App\Entity\UserWallet;

class UserWalletEntityTransformer
{
    public function __construct(
        private readonly RetrieveSystemWalletService $retrieveSystemWalletService,
    ) {
    }

    public function fromUserAndAddressToUserWalletEntity(User $user, string $address): UserWallet
    {
        $systemWallet = $this->retrieveSystemWalletService->retrieve();

        $userWallet = new UserWallet();
        $userWallet->setUsr($user);
        $userWallet->setAddress($address);
        $userWallet->setCreatedAt(new \DateTimeImmutable());
        $userWallet->setNetwork($systemWallet->getBlockchainNetwork()->getBlockchain()->getName() . ' -- ' . $systemWallet->getBlockchainNetwork()->getName());

        return $userWallet;
    }
}
