<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\UserContract\DTO\Input;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

readonly class UserContractWithdrawnInput
{
    public function __construct(
        #[NotBlank(message: 'Amount cannot be empty')]
        #[GreaterThan(0, message: 'Amount must be greather than 0')]
        public readonly int|float $amount,
    ) {
    }
}
