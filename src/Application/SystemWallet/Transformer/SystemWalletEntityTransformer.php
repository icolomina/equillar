<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\SystemWallet\Transformer;

use App\Domain\Crypt\Aead\AeadCryptedValue;
use App\Entity\BlockchainNetwork;
use App\Entity\SystemWallet;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SystemWalletEntityTransformer
{
    public function __construct(
        private readonly NormalizerInterface $serializer,
    ) {
    }

    public function fromBlockchainNetworkToEntity(BlockchainNetwork $blockchainNetwork, string $address, bool $asDefault = true): SystemWallet
    {
        $systemWallet = new SystemWallet();
        $systemWallet->setAddress($address);
        $systemWallet->setBlockchainNetwork($blockchainNetwork);
        $systemWallet->setDefaultWallet($asDefault);
        $systemWallet->setCreatedAt(new \DateTimeImmutable());

        return $systemWallet;
    }

    public function updateEntityWithCryptedValue(SystemWallet $systemWallet, AeadCryptedValue $cryptedValue): SystemWallet
    {
        $cryptedValueRaw = $this->serializer->normalize($cryptedValue);
        $systemWallet->setPrivateKey($cryptedValueRaw);

        return $systemWallet;
    }
}
