<?php

namespace App\Entity\Contract;

use App\Entity\User;
use App\Repository\Contract\ContractWithdrawalRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity(repositoryClass: ContractWithdrawalRequestRepository::class)]
class ContractWithdrawalRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contract $contract = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $requestedAt = null;

    #[ORM\Column]
    private ?float $requestedAmount = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $requestedBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $validUntil = null;

    #[ORM\Column(length: 255)]
    private ?string $uuid = null;

    #[ORM\OneToOne(mappedBy: 'contractWithdrawalRequest', targetEntity: ContractWithdrawalApproval::class, cascade: ['persist', 'remove'])]
    private ?ContractWithdrawalApproval $withdrawalApproval = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $confirmUrl = null;

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

    public function getRequestedBy(): ?User
    {
        return $this->requestedBy;
    }

    public function setRequestedBy(?User $requestedBy): static
    {
        $this->requestedBy = $requestedBy;

        return $this;
    }

    public function getValidUntil(): ?\DateTimeImmutable
    {
        return $this->validUntil;
    }

    public function setValidUntil(\DateTimeImmutable $validUntil): static
    {
        $this->validUntil = $validUntil;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getWithdrawalApproval(): ?ContractWithdrawalApproval
    {
        return $this->withdrawalApproval;
    }

    public function setWithdrawalApproval(?ContractWithdrawalApproval $withdrawalApproval): self
    {
        $this->withdrawalApproval = $withdrawalApproval;

        // Asegurarse que la relación inversa está sincronizada
        if ($withdrawalApproval && $withdrawalApproval->getContractWithdrawalRequest() !== $this) {
            $withdrawalApproval->setContractWithdrawalRequest($this);
        }

        return $this;
    }

    public function getConfirmUrl(): ?string
    {
        return $this->confirmUrl;
    }

    public function setConfirmUrl(?string $confirmUrl): static
    {
        $this->confirmUrl = $confirmUrl;

        return $this;
    }
}
