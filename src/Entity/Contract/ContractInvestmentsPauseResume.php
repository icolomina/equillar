<?php

namespace App\Entity\Contract;

use App\Entity\ContractTransaction;
use App\Repository\Contract\ContractInvestmentsPauseResumeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity(repositoryClass: ContractInvestmentsPauseResumeRepository::class)]
class ContractInvestmentsPauseResume
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contract $contract = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 25)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $currentFunds = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reason = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContractTransaction $contractTransaction = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCurrentFunds(): ?float
    {
        return $this->currentFunds;
    }

    public function setCurrentFunds(float $currentFunds): static
    {
        $this->currentFunds = $currentFunds;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): static
    {
        $this->reason = $reason;

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
