<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\Contract\DTO\Output;


readonly class ContractBalanceMovementDtoOutput
{
    public function __construct(
        public int $id,
        public string $contractName,
        public float $amount,
        public string $segmentFrom,
        public string $segmentTo,
        public string $createdAt,
        public ?string $movedAt,
        public string $status,
        public ?string $hash = null
    ){}
}
