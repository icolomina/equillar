<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\Contract\DTO\Output;


readonly class ContractWithdrawalApprovalDtoOutput
{
    public function __construct(
        public ContractWithdrawalRequestDtoOutput $contractWithdrawalRequestDtoOutput,
        public ?string $approvedAt,
        public string $status,
    ) {
    }
}
