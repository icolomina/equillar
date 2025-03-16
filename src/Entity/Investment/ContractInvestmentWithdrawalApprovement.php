<?php

namespace App\Entity\Investment;

use App\Entity\ContractTransaction;
use App\Repository\Investment\ContractInvestmentWithdrawalApprovementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractInvestmentWithdrawalApprovementRepository::class)]
class ContractInvestmentWithdrawalApprovement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContractInvestmentWithdrawalRequest $contractInvestmentWithdrawalRequest = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $approvedAt = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContractTransaction $contractTransaction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContractInvestmentWithdrawalRequest(): ?ContractInvestmentWithdrawalRequest
    {
        return $this->contractInvestmentWithdrawalRequest;
    }

    public function setContractInvestmentWithdrawalRequest(ContractInvestmentWithdrawalRequest $contractInvestmentWithdrawalRequest): static
    {
        $this->contractInvestmentWithdrawalRequest = $contractInvestmentWithdrawalRequest;

        return $this;
    }

    public function getApprovedAt(): ?\DateTimeImmutable
    {
        return $this->approvedAt;
    }

    public function setApprovedAt(?\DateTimeImmutable $approvedAt): static
    {
        $this->approvedAt = $approvedAt;

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
}
