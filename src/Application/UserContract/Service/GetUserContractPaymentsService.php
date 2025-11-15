<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\UserContract\Service;

use App\Application\UserContract\Transformer\UserContractPaymentEntityTransformer;
use App\Entity\User;
use App\Persistence\UserContract\UserContractPaymentStorageInterface;
use App\Presentation\UserContract\DTO\Output\UserContractPaymentDtoOutput;

class GetUserContractPaymentsService
{
    public function __construct(
        private readonly UserContractPaymentStorageInterface $userContractPaymentStorage,
        private readonly UserContractPaymentEntityTransformer $userContractPaymentEntityTransformer,
    ) {
    }

    /**
     * @return UserContractPaymentDtoOutput[]
     */
    public function getUserContractPayments(User $user): array
    {
        $userContractPayments = $this->userContractPaymentStorage->getByUser($user);

        return $this->userContractPaymentEntityTransformer->fromEntitiesToOutputDtos($userContractPayments);
    }
}
