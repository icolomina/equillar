<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\SystemWallet\Service;

use App\Entity\SystemWallet;
use App\Persistence\SystemWallet\SystemWalletStorageInterface;

class RetrieveSystemWalletService
{
    public function __construct(
        private readonly SystemWalletStorageInterface $systemWalletStorage,
    ) {
    }

    public function retrieve(): SystemWallet
    {
        $defaultWallet = $this->systemWalletStorage->getDefaultWallet();
        return $defaultWallet;
    }
}
