<?php

namespace App\Entity\Contract;

use App\Entity\ContractTransaction;
use App\Repository\Contract\UserContractPaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity(repositoryClass: UserContractPaymentRepository::class)]
class UserContractPayment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserContract $userContract = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hash = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalClaimed = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $paidAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?ContractTransaction $transaction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserContract(): ?UserContract
    {
        return $this->userContract;
    }

    public function setUserContract(?UserContract $userContract): static
    {
        $this->userContract = $userContract;

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

    public function getTotalClaimed(): ?float
    {
        return $this->totalClaimed;
    }

    public function setTotalClaimed(?float $totalClaimed): static
    {
        $this->totalClaimed = $totalClaimed;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTimeImmutable $paidAt): static
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    public function getTransaction(): ?ContractTransaction
    {
        return $this->transaction;
    }

    public function setTransaction(ContractTransaction $transaction): static
    {
        $this->transaction = $transaction;

        return $this;
    }
}
