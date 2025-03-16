<?php

namespace App\Persistence\Investment\Contract\Doctrine;

use App\Domain\Contract\ContractStatus;
use App\Entity\Investment\ContractInvestment;
use App\Entity\User;
use App\Persistence\Investment\Contract\ContractInvestmentStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class ContractInvestmentDoctrineStorage extends AbstractDoctrineStorage implements ContractInvestmentStorageInterface
{
    public function getContractsByIssuer(User $issuer): array
    {
        return $this->em->getRepository(ContractInvestment::class)->findBy(['issuer' => $issuer]);
    }

    public function getAllContracts(): array
    {
        return $this->em->getRepository(ContractInvestment::class)->findAll();
    }

    public function getInitializedContracts(): array
    {
        return $this->em->getRepository(ContractInvestment::class)->findBy(['status' => 'ACTIVE']);
    }

    public function getContractById(int $id): ?ContractInvestment
    {
        return $this->em->getRepository(ContractInvestment::class)->find($id);
    }

    public function getContractByAddress(string $address): ?ContractInvestment
    {
        return $this->em->getRepository(ContractInvestment::class)->findOneBy(['address' => $address]);
    }

    public function markContractAsInitalized(ContractInvestment $contract, string $contractAddress, string $projectAddress, int $returnType, int $returnMonths, int $minPerInvestment): void
    {
        $contract->setReturnMonths($returnMonths);
        $contract->setReturnType($returnType);
        $contract->setMinPerInvestment($minPerInvestment);
        $contract->setInitializedAt(new \DateTimeImmutable());
        $contract->setInitialized(true);
        $contract->setAddress($contractAddress);
        $contract->setStatus(ContractStatus::ACTIVE->name);
        $this->saveContract($contract);
    }

    public function markContractAsFundsReached(ContractInvestment $contract): void
    {
        $contract->setFundsReached(true);
        $this->saveContract($contract);
    }

    public function markContractAsApproved(ContractInvestment $contract): void
    {
        $contract->setStatus(ContractStatus::APPROVED->name);
        $contract->setApprovedAt(new \DateTimeImmutable());
        $this->saveContract($contract);
    }

    public function saveContract(ContractInvestment $contract): void
    {
        $this->persistAndFlush($contract);
    }
}
