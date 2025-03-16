<?php

namespace App\Entity\Investment;

use App\Entity\User;
use App\Entity\UserWallet;
use App\Repository\Investment\UserContractInvestmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserContractInvestmentRepository::class)]
class UserContractInvestment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContractInvestment $contract = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $usr = null;

    #[ORM\Column]
    private ?float $balance = null;

    #[ORM\Column]
    private ?float $interests = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column(length: 255)]
    private ?string $hash = null;

    #[ORM\Column]
    private ?float $totalCharged = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastPaymentReceivedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserWallet $userWallet = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $claimableAt = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

    /**
     * @var Collection<int, ClaimTransaction>
     */
    #[ORM\OneToMany(targetEntity: ClaimTransaction::class, mappedBy: 'userContractInvestment', orphanRemoval: true)]
    private Collection $claimTransactions;

    /**
     * @var Collection<int, UserContractInvestmentClaim>
     */
    #[ORM\OneToMany(targetEntity: UserContractInvestmentClaim::class, mappedBy: 'userContractInvestment', orphanRemoval: true)]
    private Collection $claims;

    #[ORM\Column(nullable: true)]
    private ?float $regularPayment = null;

    public function __construct()
    {
        $this->claimTransactions = new ArrayCollection();
        $this->claims = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContract(): ?ContractInvestment
    {
        return $this->contract;
    }

    public function setContract(?ContractInvestment $contract): static
    {
        $this->contract = $contract;

        return $this;
    }

    public function getUsr(): ?User
    {
        return $this->usr;
    }

    public function setUsr(?User $usr): static
    {
        $this->usr = $usr;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getInterests(): ?float
    {
        return $this->interests;
    }

    public function setInterests(float $interests): static
    {
        $this->interests = $interests;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

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

    public function getTotalCharged(): ?float
    {
        return $this->totalCharged;
    }

    public function setTotalCharged(float $totalCharged): static
    {
        $this->totalCharged = $totalCharged;

        return $this;
    }

    public function getLastPaymentReceivedAt(): ?\DateTimeImmutable
    {
        return $this->lastPaymentReceivedAt;
    }

    public function setLastPaymentReceivedAt(?\DateTimeImmutable $lastPaymentReceivedAt): static
    {
        $this->lastPaymentReceivedAt = $lastPaymentReceivedAt;

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

    public function getUserWallet(): ?UserWallet
    {
        return $this->userWallet;
    }

    public function setUserWallet(?UserWallet $userWallet): static
    {
        $this->userWallet = $userWallet;

        return $this;
    }

    public function getClaimableAt(): ?\DateTimeImmutable
    {
        return $this->claimableAt;
    }

    public function setClaimableAt(\DateTimeImmutable $claimableAt): static
    {
        $this->claimableAt = $claimableAt;

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

    /**
     * @return Collection<int, ClaimTransaction>
     */
    public function getClaimTransactions(): Collection
    {
        return $this->claimTransactions;
    }

    public function addClaimTransaction(ClaimTransaction $claimTransaction): static
    {
        if (!$this->claimTransactions->contains($claimTransaction)) {
            $this->claimTransactions->add($claimTransaction);
            $claimTransaction->setUserContractInvestment($this);
        }

        return $this;
    }

    public function removeClaimTransaction(ClaimTransaction $claimTransaction): static
    {
        if ($this->claimTransactions->removeElement($claimTransaction)) {
            // set the owning side to null (unless already changed)
            if ($claimTransaction->getUserContractInvestment() === $this) {
                $claimTransaction->setUserContractInvestment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserContractInvestmentClaim>
     */
    public function getClaims(): Collection
    {
        return $this->claims;
    }

    public function addClaim(UserContractInvestmentClaim $claim): static
    {
        if (!$this->claims->contains($claim)) {
            $this->claims->add($claim);
            $claim->setUserContractInvestment($this);
        }

        return $this;
    }

    public function removeClaim(UserContractInvestmentClaim $claim): static
    {
        if ($this->claims->removeElement($claim)) {
            // set the owning side to null (unless already changed)
            if ($claim->getUserContractInvestment() === $this) {
                $claim->setUserContractInvestment(null);
            }
        }

        return $this;
    }

    public function getRegularPayment(): ?float
    {
        return $this->regularPayment;
    }

    public function setRegularPayment(?float $regularPayment): static
    {
        $this->regularPayment = $regularPayment;

        return $this;
    }
}
