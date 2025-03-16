<?php

namespace App\Entity\Investment;

use App\Entity\ContractTransaction;
use App\Repository\Investment\ContractInvestmentWithdrawalRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractInvestmentWithdrawalRequestRepository::class)]
class ContractInvestmentWithdrawalRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContractInvestment $contractInvestment = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $requestedAt = null;

    #[ORM\Column]
    private ?float $requestedAmount = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContractTransaction $contractTransaction = null;

    #[ORM\Column(length: 255)]
    private ?string $hash = null;

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

    public function getRequestedAt(): ?\DateTimeImmutable
    {
        return $this->requestedAt;
    }

    public function setRequestedAt(\DateTimeImmutable $requestedAt): static
    {
        $this->requestedAt = $requestedAt;

        return $this;
    }

    public function getRequestedAmount(): ?float
    {
        return $this->requestedAmount;
    }

    public function setRequestedAmount(float $requestedAmount): static
    {
        $this->requestedAmount = $requestedAmount;

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

    public function getContractTransaction(): ?ContractTransaction
    {
        return $this->contractTransaction;
    }

    public function setContractTransaction(ContractTransaction $contractTransaction): static
    {
        $this->contractTransaction = $contractTransaction;

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
}
