<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractReserveFundContributionTransformer;
use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Entity\Contract\Contract;
use App\Entity\User;
use App\Persistence\PersistorInterface;
use App\Presentation\Contract\DTO\Input\CreateContractReserveFundContributionDtoInput;
use App\Presentation\Contract\DTO\Output\ContractReserveFundContributionCreatedDtoOutput;

class CreateReserveFundContributionService
{
    public function __construct(
        private readonly ContractReserveFundContributionTransformer $contractReserveFundContributionTransformer,
        private readonly StellarAccountLoader $stellarAccountLoader,
        private readonly PersistorInterface $persistor,
    ) {
    }

    public function createReserveFundContribution(Contract $contract, CreateContractReserveFundContributionDtoInput $createContractReserveFundContributionDtoInput, User $user): ContractReserveFundContributionCreatedDtoOutput
    {
        $contractReserveFundContribution = $this->contractReserveFundContributionTransformer->fromAmountAndUserToEntity($user, $contract, $createContractReserveFundContributionDtoInput->amount);
        $this->persistor->persistAndFlush($contractReserveFundContribution);

        $systemStellarAddress = $this->stellarAccountLoader->getKeyPair()->getAccountId();

        return $this->contractReserveFundContributionTransformer->fromEntityToContractReserveFundContributionCreatedDtoOutput($contractReserveFundContribution, $systemStellarAddress);
    }
}
