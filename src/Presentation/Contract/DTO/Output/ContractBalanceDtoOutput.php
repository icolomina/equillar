<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\Contract\DTO\Output;


readonly class ContractBalanceDtoOutput
{
    public function __construct(
        public float $available,
        public float $reserveFund,
        public float $commision,
        public float $fundsReceived,
        public float $payments,
        public float $projectWithdrawals,
        public float $reserveFundContributions,
        public float $percentajeFundsReceived,
        public float $availableToReserveMovements
    ) {
    }
}
