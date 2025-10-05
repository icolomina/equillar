<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractReserveFundContributionTransformer;
use App\Entity\Contract\Contract;
use App\Entity\User;
use App\Persistence\Contract\ContractReserveFundContributionStorageInterface;

class GetContractReserveFundContributionsService
{
    public function __construct(
        private readonly ContractReserveFundContributionStorageInterface $contractReserveFundContributionStorage,
        private readonly ContractReserveFundContributionTransformer $contractReserveFundContributionTransformer,
    ) {
    }

    public function getContractReserveFundContributions(Contract $contract): array
    {
        $reserveFundContributions = $this->contractReserveFundContributionStorage->getByContract($contract);

        return $this->contractReserveFundContributionTransformer->fromEntitiesToOutputDtos($reserveFundContributions);
    }

    public function getReserveFundContributions(User $user): array
    {
        $reserveFundContributions = ($user->isAdmin())
            ? $this->contractReserveFundContributionStorage->getAll()
            : $this->contractReserveFundContributionStorage->getByUser($user)
        ;

        return $this->contractReserveFundContributionTransformer->fromEntitiesToOutputDtos($reserveFundContributions);
    }
}
