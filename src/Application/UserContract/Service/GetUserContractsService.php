<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\UserContract\Service;

use App\Application\UserContract\Transformer\UserContractEntityTransformer;
use App\Entity\Contract\UserContract;
use App\Entity\User;
use App\Persistence\UserContract\UserContractStorageInterface;
use App\Presentation\UserContract\DTO\Output\UserContractDtoOutput;

class GetUserContractsService
{
    public function __construct(
        private readonly UserContractStorageInterface $userContractInvestmentStorage,
        private readonly UserContractEntityTransformer $userContractInvestmentEntityTransformer,
    ) {
    }

    public function getUserContracts(User $user): array
    {
        $userContracts = $this->userContractInvestmentStorage->getByUser($user);

        return $this->userContractInvestmentEntityTransformer->fromEntitiesToOutputDtos($userContracts);
    }

    public function getUserContract(UserContract $userContract): UserContractDtoOutput
    {
        return $this->userContractInvestmentEntityTransformer->fromEntityToOutputDto($userContract);
    }
}
