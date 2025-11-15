<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\Contract\Doctrine;

use App\Domain\Contract\ContractStatus;
use App\Entity\Contract\Contract;
use App\Entity\User;
use App\Persistence\Contract\ContractStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class ContractDoctrineStorage extends AbstractDoctrineStorage implements ContractStorageInterface
{
    public function getContractsByIssuer(User $issuer): array
    {
        return $this->em->getRepository(Contract::class)->findContractsByIssuerWithBalance($issuer);
    }

    public function getAllContracts(): array
    {
        return $this->em->getRepository(Contract::class)->findAllContractsWithBalance();
    }

    public function getInitializedContracts(): array
    {
        return $this->em->getRepository(Contract::class)->findBy(['status' => 'ACTIVE']);
    }

    public function getContractById(string|int $id): ?Contract
    {
        return $this->em->getRepository(Contract::class)->find($id);
    }

    public function getContractByAddress(string $address): ?Contract
    {
        return $this->em->getRepository(Contract::class)->findOneBy(['address' => $address]);
    }

    public function markContractAsInitalized(Contract $contract, string $contractAddress, string $projectAddress, int $returnType, int $returnMonths, int $minPerInvestment): void
    {
        $contract->setReturnMonths($returnMonths);
        $contract->setReturnType($returnType);
        $contract->setMinPerInvestment($minPerInvestment);
        $contract->setInitializedAt(new \DateTimeImmutable());
        $contract->setInitialized(true);
        $contract->setAddress($contractAddress);
        $contract->setStatus(ContractStatus::ACTIVE->name);
    }

    public function markContractAsFundsReached(Contract $contract): void
    {
        $contract->setFundsReached(true);
    }

    public function markContractAsApproved(Contract $contract): void
    {
        $contract->setStatus(ContractStatus::APPROVED->name);
        $contract->setApprovedAt(new \DateTimeImmutable());
    }
}
