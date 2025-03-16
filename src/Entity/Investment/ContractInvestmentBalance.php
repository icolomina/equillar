<?php

namespace App\Entity\Investment;

use App\Entity\ContractTransaction;
use App\Repository\Investment\ContractInvestmentBalanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractInvestmentBalanceRepository::class)]
class ContractInvestmentBalance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'balance', cascade: ['persist', 'remove'])]
    private ?ContractInvestment $contractInvestment = null;

    #[ORM\Column]
    private ?float $available = null;

    #[ORM\Column]
    private ?float $reserveFund = null;

    #[ORM\Column]
    private ?float $comission = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ContractTransaction $contractTransaction = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContractInvestment(): ?ContractInvestment
    {
        return $this->contractInvestment;
    }

    public function setContractInvestment(?ContractInvestment $contractInvestment): static
    {
        $this->contractInvestment = $contractInvestment;

        return $this;
    }

    public function getAvailable(): ?float
    {
        return $this->available;
    }

    public function setAvailable(float $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function getReserveFund(): ?float
    {
        return $this->reserveFund;
    }

    public function setReserveFund(float $reserveFund): static
    {
        $this->reserveFund = $reserveFund;

        return $this;
    }

    public function getComission(): ?float
    {
        return $this->comission;
    }

    public function setComission(float $comission): static
    {
        $this->comission = $comission;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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
}
