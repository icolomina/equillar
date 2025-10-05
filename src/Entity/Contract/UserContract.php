<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Entity\Contract;

use App\Entity\User;
use App\Entity\UserWallet;
use App\Repository\Contract\UserContractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserContractRepository::class)]
class UserContract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contract $contract = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $usr = null;

    #[ORM\Column]
    private ?float $balance = null;

    #[ORM\Column(nullable: true)]
    private ?float $interests = null;

    #[ORM\Column(nullable: true)]
    private ?float $total = null;

    #[ORM\Column(length: 255)]
    private ?string $hash = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalCharged = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastPaymentReceivedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserWallet $userWallet = null;

    #[ORM\Column]
    private ?int $claimableTs = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

    /**
     * @var Collection<int, UserContractPayment>
     */
    #[ORM\OneToMany(targetEntity: UserContractPayment::class, mappedBy: 'userContractPayment', orphanRemoval: true)]
    private Collection $payments;

    #[ORM\Column(nullable: true)]
    private ?float $regularPayment = null;

    #[ORM\Column(nullable: true)]
    private ?float $commission = null;

    #[ORM\Column(nullable: true)]
    private ?float $realDeposited = null;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

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

    public function getUsr(): ?User
    {
        return $this->usr;
    }

    public function setUsr(?User $usr): static
    {
        $this->usr = $usr;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getInterests(): ?float
    {
        return $this->interests;
    }

    public function setInterests(float $interests): static
    {
        $this->interests = $interests;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): static
    {
        $this->hash = $hash;

        return $this;
    }

    public function getTotalCharged(): ?float
    {
        return $this->totalCharged;
    }

    public function setTotalCharged(float $totalCharged): static
    {
        $this->totalCharged = $totalCharged;

        return $this;
    }

    public function getLastPaymentReceivedAt(): ?\DateTimeImmutable
    {
        return $this->lastPaymentReceivedAt;
    }

    public function setLastPaymentReceivedAt(?\DateTimeImmutable $lastPaymentReceivedAt): static
    {
        $this->lastPaymentReceivedAt = $lastPaymentReceivedAt;

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

    public function getUserWallet(): ?UserWallet
    {
        return $this->userWallet;
    }

    public function setUserWallet(?UserWallet $userWallet): static
    {
        $this->userWallet = $userWallet;

        return $this;
    }

    public function getClaimableTs(): ?int
    {
        return $this->claimableTs;
    }

    public function setClaimableTs(?int $claimableTs): static
    {
        $this->claimableTs = $claimableTs;

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

    /**
     * @return Collection<int, UserContractPayment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function getRegularPayment(): ?float
    {
        return $this->regularPayment;
    }

    public function setRegularPayment(?float $regularPayment): static
    {
        $this->regularPayment = $regularPayment;

        return $this;
    }

    public function getCommission(): ?float
    {
        return $this->commission;
    }

    public function setCommission(?float $commission): static
    {
        $this->commission = $commission;

        return $this;
    }

    public function getRealDeposited(): ?float
    {
        return $this->realDeposited;
    }

    public function setRealDeposited(?float $realDeposited): static
    {
        $this->realDeposited = $realDeposited;

        return $this;
    }
}
