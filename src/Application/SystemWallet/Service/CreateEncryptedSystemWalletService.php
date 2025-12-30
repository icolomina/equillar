<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\SystemWallet\Service;

use App\Application\SystemWallet\Transformer\SystemWalletEntityTransformer;
use App\Domain\Crypt\Aead\Service\EntityAeadEncryptor;
use App\Entity\BlockchainNetwork;
use App\Entity\SystemWallet;
use App\Persistence\PersistorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CreateEncryptedSystemWalletService
{
    public function __construct(
        private readonly SystemWalletEntityTransformer $transformer,
        private readonly EntityAeadEncryptor $entityAeadEncryptor,
        private readonly NormalizerInterface $normalizer,
        private readonly PersistorInterface $persistor
    ) {
    }

    public function create(BlockchainNetwork $blockchainNetwork, string $address, string $secretSeed, bool $asDefault = true): SystemWallet {
        
        // 1. Create entity structure
        $systemWallet = $this->transformer->fromBlockchainNetworkToEntity(
            $blockchainNetwork,
            $address,
            $asDefault
        );

        // 2. Encrypt secret seedp
        $cryptedValue = $this->entityAeadEncryptor->encryptEntity($systemWallet, $secretSeed);
        $cryptedValueRaw = $this->normalizer->normalize($cryptedValue);
        $systemWallet->setPrivateKey($cryptedValueRaw);

        // 3. Persist with encrypted data
        $this->persistor->persistAndFlush($systemWallet);

        return $systemWallet;
    }
}
