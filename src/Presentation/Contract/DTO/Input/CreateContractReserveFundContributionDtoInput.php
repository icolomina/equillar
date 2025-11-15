<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\Contract\DTO\Input;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

readonly class CreateContractReserveFundContributionDtoInput
{
    public function __construct(
        #[NotBlank(message: 'Amount cannot be empty')]
        #[GreaterThan(0, message: 'Amount must be greater than 0')]
        public float|int $amount,
    ) {
    }
}
