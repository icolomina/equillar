<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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
        $systemWalletData = $this->retrieveSystemWalletService->retrieve();

        $userWallet = new UserWallet();
        $userWallet->setUsr($user);
        $userWallet->setAddress($address);
        $userWallet->setCreatedAt(new \DateTimeImmutable());
        $userWallet->setNetwork($systemWalletData->blockchain.' -- '.$systemWalletData->network);

        return $userWallet;
    }
}
