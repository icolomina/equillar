<?php

namespace App\Entity\Contract;

use App\Entity\ContractTransaction;
use App\Repository\Contract\ContractBalanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractBalanceRepository::class)]
class ContractBalance
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $available = null;

    #[ORM\Column(nullable: true)]
    private ?float $reserveFund = null;

    #[ORM\Column(nullable: true)]
    private ?float $comission = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ContractTransaction $contractTransaction = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'contractBalances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contract $contract = null;

    #[ORM\Column(nullable: true)]
    private ?float $fundsReceived = null;

    #[ORM\Column(nullable: true)]
    private ?float $payments = null;

    #[ORM\Column(nullable: true)]
    private ?float $reserveContributions = null;

    #[ORM\Column(nullable: true)]
    private ?float $projectWithdrawals = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): static
    {
        $this->contract = $contract;

        return $this;
    }

    public function getFundsReceived(): ?float
    {
        return $this->fundsReceived;
    }

    public function setFundsReceived(float $fundsReceived): static
    {
        $this->fundsReceived = $fundsReceived;

        return $this;
    }

    public function getPayments(): ?float
    {
        return $this->payments;
    }

    public function setPayments(float $payments): static
    {
        $this->payments = $payments;

        return $this;
    }

    public function getReserveContributions(): ?float
    {
        return $this->reserveContributions;
    }

    public function setReserveContributions(float $reserveContributions): static
    {
        $this->reserveContributions = $reserveContributions;

        return $this;
    }

    public function getProjectWithdrawals(): ?float
    {
        return $this->projectWithdrawals;
    }

    public function setProjectWithdrawals(float $projectWithdrawals): static
    {
        $this->projectWithdrawals = $projectWithdrawals;

        return $this;
    }
}
