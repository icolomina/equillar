<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\Contract\DTO\Output;


readonly class ContractWithdrawalRequestDtoOutput
{
    public function __construct(
        public int $id,
        public string $contractLabel,
        public string $requestedAt,
        public string $requestedBy,
        public string $requestedAmount,
        public ?string $status,
        public ?string $approvedAt = null,
        public ?string $hash = null,
    ) {
    }
}
