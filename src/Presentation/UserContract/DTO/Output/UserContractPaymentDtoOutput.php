<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\UserContract\DTO\Output;


class UserContractPaymentDtoOutput
{
    public function __construct(
        public readonly string $id,
        public readonly string $projectIssuer,
        public readonly string $projectName,
        public readonly ?string $hash,
        public readonly string $paymentEmittedAt,
        public readonly string $totalToReceive,
        public readonly string $status,
        public readonly ?string $paymentPaidAt = null,
        public string $totalReceived = 'Reception pending',
    ) {
    }
}
