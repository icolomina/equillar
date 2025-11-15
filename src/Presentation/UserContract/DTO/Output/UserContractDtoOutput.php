<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\UserContract\DTO\Output;


use App\Presentation\Token\DTO\Output\TokenContractDtoOutput;

class UserContractDtoOutput
{
    public function __construct(
        public readonly string $id,
        public readonly string $contractIssuer,
        public readonly string $contractLabel,
        public readonly string $contractAddress,
        public readonly TokenContractDtoOutput $tokenContract,
        public readonly float $rate,
        public readonly string $createdAt,
        public readonly string $withdrawalDate,
        public readonly float $deposited,
        public readonly ?float $interest,
        public readonly ?float $commission,
        public readonly ?float $total,
        public readonly ?string $hash,
        public readonly ?string $status,
        public readonly string $paymentType,
        public readonly array $paymentsCalendar,
    ) {
    }
}
