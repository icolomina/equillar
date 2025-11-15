<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\Contract\DTO\Output;


readonly class ContractBalanceMovementCreatedDtoOutput
{
    public function __construct(
        public string $segmentFrom,
        public string $segmentTo,
        public string $status,
        public ?float $amount
    ){}
}
