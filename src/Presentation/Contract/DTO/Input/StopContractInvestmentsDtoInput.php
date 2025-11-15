<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\Contract\DTO\Input;

readonly class StopContractInvestmentsDtoInput
{
    public function __construct(
        public ?string $reason = null,
    ) {
    }
}
