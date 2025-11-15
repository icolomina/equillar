<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Entity\Contract;


use App\Entity\ContractCode;
use App\Entity\ContractTransaction;
use App\Entity\Token;
use App\Entity\User;
use App\Repository\Contract\ContractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractRepository::class)]
class Contract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $issuer = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $initializedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $approvedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Token $token = null;

    #[ORM\Column]
    private ?float $rate = null;

    #[ORM\Column]
    private ?bool $initialized = null;

    #[ORM\Column]
    private ?bool $fundsReached = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?int $claimMonths = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\Column]
    private ?float $goal = null;

    #[ORM\Column(nullable: true)]
    private ?int $returnType = null;

    #[ORM\Column(nullable: true)]
    private ?int $returnMonths = null;

    #[ORM\Column(nullable: true)]
    private ?float $minPerInvestment = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $shortDescription = null;

    /**
     * @var Collection<int, ContractBalance>
     */
    #[ORM\OneToMany(targetEntity: ContractBalance::class, mappedBy: 'contract', orphanRemoval: true)]
    private Collection $contractBalances;

    /**
     * @var Collection<int, ContractWithdrawalRequest>
     */
    #[ORM\OneToMany(targetEntity: ContractWithdrawalRequest::class, mappedBy: 'contract', orphanRemoval: true)]
    private Collection $contractWithdrawalRequests;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ContractTransaction $contractTransaction = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $projectAddress = null;

    #[ORM\ManyToOne]
    private ?ContractCode $contractCode = null;

    /**
     * @var Collection<int, ContractPaymentAvailability>
     */
    #[ORM\OneToMany(targetEntity: ContractPaymentAvailability::class, mappedBy: 'contract', orphanRemoval: true)]
    private Collection $paymentAvailabilities;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastPausedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastResumedAt = null;

    public function __construct()
    {
        $this->contractBalances = new ArrayCollection();
        $this->contractWithdrawalRequests = new ArrayCollection();
        $this->paymentAvailabilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?Token
    {
        return $this->token;
    }

    public function setToken(?Token $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function isInitialized(): ?bool
    {
        return $this->initialized;
    }

    public function setInitialized(bool $initialized): static
    {
        $this->initialized = $initialized;

        return $this;
    }

    public function isFundsReached(): ?bool
    {
        return $this->fundsReached;
    }

    public function setFundsReached(bool $fundsReached): static
    {
        $this->fundsReached = $fundsReached;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getClaimMonths(): ?int
    {
        return $this->claimMonths;
    }

    public function setClaimMonths(?int $claimMonths): static
    {
        $this->claimMonths = $claimMonths;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getGoal(): ?float
    {
        return $this->goal;
    }

    public function setGoal(float $goal): static
    {
        $this->goal = $goal;

        return $this;
    }

    public function getReturnType(): ?int
    {
        return $this->returnType;
    }

    public function setReturnType(int $returnType): static
    {
        $this->returnType = $returnType;

        return $this;
    }

    public function getReturnMonths(): ?int
    {
        return $this->returnMonths;
    }

    public function setReturnMonths(?int $returnMonths): static
    {
        $this->returnMonths = $returnMonths;

        return $this;
    }

    public function getMinPerInvestment(): ?float
    {
        return $this->minPerInvestment;
    }

    public function setMinPerInvestment(?float $minPerInvestment): static
    {
        $this->minPerInvestment = $minPerInvestment;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

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

    public function getIssuer(): ?User
    {
        return $this->issuer;
    }

    public function setIssuer(?User $issuer): static
    {
        $this->issuer = $issuer;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getInitializedAt(): ?\DateTimeImmutable
    {
        return $this->initializedAt;
    }

    public function setInitializedAt(\DateTimeImmutable $initializedAt): static
    {
        $this->initializedAt = $initializedAt;

        return $this;
    }

    public function getApprovedAt(): ?\DateTimeImmutable
    {
        return $this->approvedAt;
    }

    public function setApprovedAt(\DateTimeImmutable $approvedAt): static
    {
        $this->approvedAt = $approvedAt;

        return $this;
    }

    /**
     * @return Collection<int, ContractBalance>
     */
    public function getContractBalances(): Collection
    {
        return $this->contractBalances;
    }

    public function addContractBalance(ContractBalance $contractBalance): static
    {
        if (!$this->contractBalances->contains($contractBalance)) {
            $this->contractBalances->add($contractBalance);
            $contractBalance->setContract($this);
        }

        return $this;
    }

    public function removeContractBalance(ContractBalance $contractBalance): static
    {
        if ($this->contractBalances->removeElement($contractBalance)) {
            // set the owning side to null (unless already changed)
            if ($contractBalance->getContract() === $this) {
                $contractBalance->setContract(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ContractWithdrawalRequest>
     */
    public function getContractWithdrawalRequests(): Collection
    {
        return $this->contractWithdrawalRequests;
    }

    public function addContractWithdrawalRequest(ContractWithdrawalRequest $contractWithdrawalRequest): static
    {
        if (!$this->contractWithdrawalRequests->contains($contractWithdrawalRequest)) {
            $this->contractWithdrawalRequests->add($contractWithdrawalRequest);
            $contractWithdrawalRequest->setContract($this);
        }

        return $this;
    }

    public function removeContractWithdrawalRequest(ContractWithdrawalRequest $contractWithdrawalRequest): static
    {
        if ($this->contractWithdrawalRequests->removeElement($contractWithdrawalRequest)) {
            // set the owning side to null (unless already changed)
            if ($contractWithdrawalRequest->getContract() === $this) {
                $contractWithdrawalRequest->setContract(null);
            }
        }

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

    public function getProjectAddress(): ?string
    {
        return $this->projectAddress;
    }

    public function setProjectAddress(?string $projectAddress): static
    {
        $this->projectAddress = $projectAddress;

        return $this;
    }

    public function getContractCode(): ?ContractCode
    {
        return $this->contractCode;
    }

    public function setContractCode(?ContractCode $contractCode): static
    {
        $this->contractCode = $contractCode;

        return $this;
    }

    /**
     * @return Collection<int, ContractPaymentAvailability>
     */
    public function getPaymentAvailabilities(): Collection
    {
        return $this->paymentAvailabilities;
    }

    public function addPaymentAvailability(ContractPaymentAvailability $paymentAvailability): static
    {
        if (!$this->paymentAvailabilities->contains($paymentAvailability)) {
            $this->paymentAvailabilities->add($paymentAvailability);
            $paymentAvailability->setContract($this);
        }

        return $this;
    }

    public function removePaymentAvailability(ContractPaymentAvailability $paymentAvailability): static
    {
        if ($this->paymentAvailabilities->removeElement($paymentAvailability)) {
            // set the owning side to null (unless already changed)
            if ($paymentAvailability->getContract() === $this) {
                $paymentAvailability->setContract(null);
            }
        }

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getLastPausedAt(): ?\DateTimeImmutable
    {
        return $this->lastPausedAt;
    }

    public function setLastPausedAt(?\DateTimeImmutable $lastPausedAt): static
    {
        $this->lastPausedAt = $lastPausedAt;

        return $this;
    }

    public function getLastResumedAt(): ?\DateTimeImmutable
    {
        return $this->lastResumedAt;
    }

    public function setLastResumedAt(?\DateTimeImmutable $lastResumedAt): static
    {
        $this->lastResumedAt = $lastResumedAt;

        return $this;
    }
}
