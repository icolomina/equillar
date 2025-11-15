<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Entity\Contract;


use App\Entity\ContractTransaction;
use App\Entity\User;
use App\Repository\Contract\ContractBalanceMovementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractBalanceMovementRepository::class)]
class ContractBalanceMovement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contract $contract = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 20)]
    private ?string $segmentFrom = null;

    #[ORM\Column(length: 20)]
    private ?string $segmentTo = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $movedAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ContractTransaction $contractTransaction = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $requestedBy = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getSegmentFrom(): ?string
    {
        return $this->segmentFrom;
    }

    public function setSegmentFrom(string $segmentFrom): static
    {
        $this->segmentFrom = $segmentFrom;

        return $this;
    }

    public function getSegmentTo(): ?string
    {
        return $this->segmentTo;
    }

    public function setSegmentTo(string $segmentTo): static
    {
        $this->segmentTo = $segmentTo;

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

    public function getMovedAt(): ?\DateTimeImmutable
    {
        return $this->movedAt;
    }

    public function setMovedAt(?\DateTimeImmutable $movedAt): static
    {
        $this->movedAt = $movedAt;

        return $this;
    }

    public function getContractTransaction(): ?ContractTransaction
    {
        return $this->contractTransaction;
    }

    public function setContractTransaction(?ContractTransaction $contractTransaction): static
    {
        $this->contractTransaction = $contractTransaction;

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

    public function getRequestedBy(): ?User
    {
        return $this->requestedBy;
    }

    public function setRequestedBy(?User $requestedBy): static
    {
        $this->requestedBy = $requestedBy;

        return $this;
    }
}
