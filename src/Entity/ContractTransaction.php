<?php

namespace App\Entity;

use App\Repository\ContractTransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractTransactionRepository::class)]
class ContractTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contractAddress = null;

    #[ORM\Column(length: 100)]
    private ?string $contractLabel = null;

    #[ORM\Column(length: 50)]
    private ?string $functionCalled = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $trxResult = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $trxHash = null;

    #[ORM\Column(nullable: true)]
    private ?array $trxResultData = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $trxDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $error = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContractAddress(): ?string
    {
        return $this->contractAddress;
    }

    public function setContractAddress(string $contractAddress): static
    {
        $this->contractAddress = $contractAddress;

        return $this;
    }

    public function getContractLabel(): ?string
    {
        return $this->contractLabel;
    }

    public function setContractLabel(string $contractLabel): static
    {
        $this->contractLabel = $contractLabel;

        return $this;
    }

    public function getFunctionCalled(): ?string
    {
        return $this->functionCalled;
    }

    public function setFunctionCalled(string $functionCalled): static
    {
        $this->functionCalled = $functionCalled;

        return $this;
    }

    public function getTrxResult(): ?string
    {
        return $this->trxResult;
    }

    public function setTrxResult(string $trxResult): static
    {
        $this->trxResult = $trxResult;

        return $this;
    }

    public function getTrxHash(): ?string
    {
        return $this->trxHash;
    }

    public function setTrxHash(string $trxHash): static
    {
        $this->trxHash = $trxHash;

        return $this;
    }

    public function getTrxResultData(): ?array
    {
        return $this->trxResultData;
    }

    public function setTrxResultData(?array $trxResultData): static
    {
        $this->trxResultData = $trxResultData;

        return $this;
    }

    public function getTrxDate(): ?\DateTimeImmutable
    {
        return $this->trxDate;
    }

    public function setTrxDate(\DateTimeImmutable $trxDate): static
    {
        $this->trxDate = $trxDate;

        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): static
    {
        $this->error = $error;

        return $this;
    }
}
