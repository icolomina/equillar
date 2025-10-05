<?php

namespace App\Persistence\Contract\Doctrine;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractReserveFundContribution;
use App\Entity\User;
use App\Persistence\Contract\ContractReserveFundContributionStorageInterface;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;

class ContractReserveFundContributionDoctrineStorage extends AbstractDoctrineStorage implements ContractReserveFundContributionStorageInterface
{
    public function getByUuid(string $uuid): ?ContractReserveFundContribution
    {
        return $this->em->getRepository(ContractReserveFundContribution::class)->findOneBy(['uuid' => $uuid]);
    }

    public function getByUuidAndStatus(string $uuid, string $status): ?ContractReserveFundContribution
    {
        return $this->em->getRepository(ContractReserveFundContribution::class)->findOneBy(['uuid' => $uuid, 'status' => $status]);
    }

    public function getTotalContributionsByContract(Contract $contract): int|float
    {
        $total = $this->em->getRepository(ContractReserveFundContribution::class)->sumContributionsByContract($contract);

        return $total ?? 0;
    }

    public function getByContract(Contract $contract): array
    {
        return $this->em->getRepository(ContractReserveFundContribution::class)->findBy(['contract' => $contract]);
    }

    public function getByUser(User $user): array
    {
        return $this->em->getRepository(ContractReserveFundContribution::class)->findReserveFundContributionsByIssuer($user);
    }

    public function getAll(): array
    {
        return $this->em->getRepository(ContractReserveFundContribution::class)->findAll();
    }
}
