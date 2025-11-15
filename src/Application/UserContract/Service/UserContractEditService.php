<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\UserContract\Service;

use App\Application\UserContract\Transformer\UserContractEntityTransformer;
use App\Entity\Contract\UserContract;
use App\Presentation\UserContract\DTO\Output\UserContractDtoOutput;

class UserContractEditService
{
    public function __construct(
        private readonly UserContractPaymentsCalendarService $userContractPaymentsCalendarService,
        private readonly UserContractEntityTransformer $userContractEntityTransformer,
    ) {
    }

    public function editUserContract(UserContract $userContract): UserContractDtoOutput
    {
        $calendar = $this->userContractPaymentsCalendarService->generatePaymentsCalendar($userContract);

        return $this->userContractEntityTransformer->fromEntityToOutputDto($userContract, $calendar->getCalendar());
    }
}
