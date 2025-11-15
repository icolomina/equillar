<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\SystemWallet\Transformer;

use App\Domain\Crypt\CryptedValue;
use App\Entity\BlockchainNetwork;
use App\Entity\SystemWallet;
use Symfony\Component\Serializer\SerializerInterface;

class SystemWalletEntityTransformer
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function fromBlockchainNetworkAndCrypedValueToEntity(BlockchainNetwork $blockchainNetwork, CryptedValue $cryptedValue, string $address, bool $asDefault = true): SystemWallet
    {
        $cryptedValueRaw = $this->serializer->normalize($cryptedValue);

        $systemWallet = new SystemWallet();
        $systemWallet->setPrivateKey($cryptedValueRaw);
        $systemWallet->setAddress($address);
        $systemWallet->setBlockchainNetwork($blockchainNetwork);
        $systemWallet->setDefaultWallet($asDefault);
        $systemWallet->setCreatedAt(new \DateTimeImmutable());

        return $systemWallet;
    }
}
