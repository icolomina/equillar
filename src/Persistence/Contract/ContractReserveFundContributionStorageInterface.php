<?php

namespace App\Persistence\Contract;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractReserveFundContribution;
use App\Entity\User;

interface ContractReserveFundContributionStorageInterface
{
    public function getByUuid(string $uuid): ?ContractReserveFundContribution;
    public function getByUuidAndStatus(string $uuid, string $status): ?ContractReserveFundContribution;
    public function getTotalContributionsByContract(Contract $contract): int|float|null;

    /**
     * @return ContractReserveFundContribution[]
     */
    public function getByContract(Contract $contract): array;
    
    /**
     * @return ContractReserveFundContribution[]
     */
    public function getByUser(User $user): array;
}
