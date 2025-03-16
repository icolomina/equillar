<?php

namespace App\Entity\Investment;

use App\Repository\Investment\ClaimTransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClaimTransactionRepository::class)]
class ClaimTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $txHash = null;

    #[ORM\Column(length: 10)]
    private ?string $txStatus = null;

    #[ORM\Column(nullable: true)]
    private ?array $txResultData = null;

    #[ORM\ManyToOne(inversedBy: 'claimTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserContractInvestment $userContractInvestment = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $txCreatedAt = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $amountPaid = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTxHash(): ?string
    {
        return $this->txHash;
    }

    public function setTxHash(string $txHash): static
    {
        $this->txHash = $txHash;

        return $this;
    }

    public function getTxStatus(): ?string
    {
        return $this->txStatus;
    }

    public function setTxStatus(string $txStatus): static
    {
        $this->txStatus = $txStatus;

        return $this;
    }

    public function getTxResultData(): ?array
    {
        return $this->txResultData;
    }

    public function setTxResultData(?array $txResultData): static
    {
        $this->txResultData = $txResultData;

        return $this;
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

    public function getTxCreatedAt(): ?string
    {
        return $this->txCreatedAt;
    }

    public function setTxCreatedAt(?string $txCreatedAt): static
    {
        $this->txCreatedAt = $txCreatedAt;

        return $this;
    }

    public function getAmountPaid(): ?string
    {
        return $this->amountPaid;
    }

    public function setAmountPaid(?string $amountPaid): static
    {
        $this->amountPaid = $amountPaid;

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
}
