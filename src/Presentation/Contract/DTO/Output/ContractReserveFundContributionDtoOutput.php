<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\Contract\DTO\Output;


readonly class ContractReserveFundContributionDtoOutput
{
    public function __construct(
        public int $id,
        public string $contractLabel,
        public float $amount,
        public string $status,
        public string $createdAt,
        public ?string $receivedAt,
        public ?string $transferredAt,
        public ?string $hash = null
    ) {
    }
}
