<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
