<?php

namespace App\Entity\Investment;

use App\Entity\Contract;
use App\Entity\Token;
use App\Repository\Investment\ContractInvestmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractInvestmentRepository::class)]
class ContractInvestment extends Contract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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

    #[ORM\OneToOne(mappedBy: 'contractInvestment', cascade: ['persist', 'remove'])]
    private ?ContractInvestmentBalance $balance = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $shortDescription = null;

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

    public function getBalance(): ?ContractInvestmentBalance
    {
        return $this->balance;
    }

    public function setBalance(?ContractInvestmentBalance $balance): static
    {
        // unset the owning side of the relation if necessary
        if ($balance === null && $this->balance !== null) {
            $this->balance->setContractInvestment(null);
        }

        // set the owning side of the relation if necessary
        if ($balance !== null && $balance->getContractInvestment() !== $this) {
            $balance->setContractInvestment($this);
        }

        $this->balance = $balance;

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
}
