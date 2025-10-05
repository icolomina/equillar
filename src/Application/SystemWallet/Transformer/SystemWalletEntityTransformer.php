<?php

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
