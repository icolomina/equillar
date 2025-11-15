<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Entity;

use App\Repository\SystemWalletRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SystemWalletRepository::class)]
class SystemWallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private array $privateKey = [];

    #[ORM\Column]
    private ?bool $defaultWallet = null;

    #[ORM\ManyToOne(inversedBy: 'wallets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BlockchainNetwork $blockchainNetwork = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPrivateKey(): array
    {
        return $this->privateKey;
    }

    public function setPrivateKey(array $privateKey): static
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    public function isDefaultWallet(): ?bool
    {
        return $this->defaultWallet;
    }

    public function setDefaultWallet(bool $defaultWallet): static
    {
        $this->defaultWallet = $defaultWallet;

        return $this;
    }

    public function getBlockchainNetwork(): ?BlockchainNetwork
    {
        return $this->blockchainNetwork;
    }

    public function setBlockchainNetwork(?BlockchainNetwork $blockchainNetwork): static
    {
        $this->blockchainNetwork = $blockchainNetwork;

        return $this;
    }
}
