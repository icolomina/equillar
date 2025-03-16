<?php

namespace App\Entity\Investment;

use App\Entity\ContractTransaction;
use App\Repository\Investment\UserContractInvestmentClaimRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserContractInvestmentClaimRepository::class)]
class UserContractInvestmentClaim
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'claims')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserContractInvestment $userContractInvestment = null;

    #[ORM\Column(length: 255)]
    private ?string $hash = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalClaimed = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $claimedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContractTransaction $transaction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserContractInvestment(): ?UserContractInvestment
    {
        return $this->userContractInvestment;
    }

    public function setUserContractInvestment(?UserContractInvestment $userContractInvestment): static
    {
        $this->userContractInvestment = $userContractInvestment;

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

    public function getTotalClaimed(): ?float
    {
        return $this->totalClaimed;
    }

    public function setTotalClaimed(?float $totalClaimed): static
    {
        $this->totalClaimed = $totalClaimed;

        return $this;
    }

    public function getClaimedAt(): ?\DateTimeImmutable
    {
        return $this->claimedAt;
    }

    public function setClaimedAt(?\DateTimeImmutable $claimedAt): static
    {
        $this->claimedAt = $claimedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    public function getTransaction(): ?ContractTransaction
    {
        return $this->transaction;
    }

    public function setTransaction(ContractTransaction $transaction): static
    {
        $this->transaction = $transaction;

        return $this;
    }
}
