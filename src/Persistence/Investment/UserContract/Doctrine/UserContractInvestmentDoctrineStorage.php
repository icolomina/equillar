<?php

namespace App\Persistence\Investment\UserContract\Doctrine;

use App\Entity\Investment\UserContractInvestment;
use App\Entity\User;
use App\Persistence\Investment\UserContract\UserContractInvestmentStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class UserContractInvestmentDoctrineStorage extends AbstractDoctrineStorage implements UserContractInvestmentStorageInterface
{

    public function getById(int $id): ?UserContractInvestment
    {
        return $this->em->getRepository(UserContractInvestment::class)->find($id);
    }

    public function getByUser(User $user): array
    {
        return $this->em->getRepository(UserContractInvestment::class)->findBy(['usr' => $user]);
    }

    public function saveUserContract(UserContractInvestment $userContractInvestment): void
    {
        $this->em->persist($userContractInvestment);
        $this->em->flush();
    }

    public function getClaimableCandidates(\DateTimeImmutable $claimableFrom, \DateTimeImmutable $lastPaymentFrom): iterable
    {
        $query = $this->em->getRepository(UserContractInvestment::class)->findClaimableCandidates($claimableFrom, $lastPaymentFrom);
        return $query->toIterable();
    }
}
