<?php

namespace App\Entity\Contract;

use App\Entity\ContractTransaction;
use App\Repository\Contract\ContractWithdrawalApprovalRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity(repositoryClass: ContractWithdrawalApprovalRepository::class)]
class ContractWithdrawalApproval
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContractWithdrawalRequest $contractWithdrawalRequest = null;

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

    public function getContractWithdrawalRequest(): ?ContractWithdrawalRequest
    {
        return $this->contractWithdrawalRequest;
    }

    public function setContractWithdrawalRequest(ContractWithdrawalRequest $contractWithdrawalRequest): static
    {
        $this->contractWithdrawalRequest = $contractWithdrawalRequest;

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
