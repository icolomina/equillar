<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\UserContract\Doctrine;

use App\Entity\Contract\Contract;
use App\Entity\Contract\UserContract;
use App\Entity\User;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;
use App\Persistence\UserContract\UserContractStorageInterface;

class UserContractDoctrineStorage extends AbstractDoctrineStorage implements UserContractStorageInterface
{
    public function getById(int $id): ?UserContract
    {
        return $this->em->getRepository(UserContract::class)->find($id);
    }

    public function getByUser(User $user): array
    {
        return $this->em->getRepository(UserContract::class)->findBy(['usr' => $user]);
    }

    public function getByUserAndContract(User $user, Contract $contract): ?UserContract
    {
        return $this->em->getRepository(UserContract::class)->findOneBy(['usr' => $user, 'contract' => $contract]);
    }

    public function saveUserContract(UserContract $userContractInvestment): void
    {
        $this->em->persist($userContractInvestment);
        $this->em->flush();
    }

    public function getClaimableCandidates(\DateTimeImmutable $claimableFrom, \DateTimeImmutable $lastPaymentFrom): iterable
    {
        $query = $this->em->getRepository(UserContract::class)->findClaimableCandidates($claimableFrom, $lastPaymentFrom);

        return $query->toIterable();
    }

    public function getPorfolioUserContracts(User $user): array
    {
        return $this->em->getRepository(UserContract::class)->findUserPortfolioContracts($user);
    }
}
