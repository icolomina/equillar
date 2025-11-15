<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Entity\Contract;

use App\Entity\ContractTransaction;
use App\Entity\User;
use App\Repository\Contract\ContractReserveFundContributionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractReserveFundContributionRepository::class)]
class ContractReserveFundContribution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $uuid = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sourceUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $receivedTransactionHash = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ContractTransaction $contractTrasaction = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $receivedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $transferredAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contract $contract = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSourceUser(): ?User
    {
        return $this->sourceUser;
    }

    public function setSourceUser(?User $sourceUser): static
    {
        $this->sourceUser = $sourceUser;

        return $this;
    }

    public function getReceivedTransactionHash(): ?string
    {
        return $this->receivedTransactionHash;
    }

    public function setReceivedTransactionHash(?string $receivedTransactionHash): static
    {
        $this->receivedTransactionHash = $receivedTransactionHash;

        return $this;
    }

    public function getContractTrasaction(): ?ContractTransaction
    {
        return $this->contractTrasaction;
    }

    public function setContractTrasaction(?ContractTransaction $contractTrasaction): static
    {
        $this->contractTrasaction = $contractTrasaction;

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

    public function getReceivedAt(): ?\DateTimeImmutable
    {
        return $this->receivedAt;
    }

    public function setReceivedat(?\DateTimeImmutable $receivedAt): static
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }

    public function getTransferredAt(): ?\DateTimeImmutable
    {
        return $this->transferredAt;
    }

    public function setTransferredAt(?\DateTimeImmutable $transferredAt): static
    {
        $this->transferredAt = $transferredAt;

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): static
    {
        $this->contract = $contract;

        return $this;
    }
}
