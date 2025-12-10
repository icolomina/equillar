<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\SystemWallet\Service;

use App\Domain\Crypt\CryptedValue;
use App\Domain\SystemWallet\SystemWalletData;
use App\Persistence\SystemWallet\SystemWalletStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RetrieveSystemWalletService
{
    public function __construct(
        private readonly SystemWalletStorageInterface $systemWalletStorage,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function retrieve(): SystemWalletData
    {
        $defaultWallet = $this->systemWalletStorage->getDefaultWallet();

        /**
         * @var CryptedValue $cryptedValue
         */
        $cryptedValue = $this->serializer->denormalize($defaultWallet->getPrivateKey(), CryptedValue::class);

        return new SystemWalletData(
            $defaultWallet->getAddress(),
            $defaultWallet->getBlockchainNetwork()->getBlockchain()->getName(),
            $defaultWallet->getBlockchainNetwork()->getLabel(),
            $defaultWallet->getBlockchainNetwork()->getUrl(),
            $defaultWallet->getBlockchainNetwork()->isTest(),
            $cryptedValue
        );
    }
}
