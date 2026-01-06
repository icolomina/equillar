<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\Contract\DTO\Output;


use App\Presentation\Token\DTO\Output\TokenContractDtoOutput;

readonly class ContractDtoOutput
{
    public function __construct(
        public string $id,
        public ?string $address,
        public TokenContractDtoOutput $tokenContract,
        public float $rate,
        public string $createdAt,
        public ?string $initializedAt,
        public ?string $approvedAt,
        public ?string $lastPausedAt,
        public ?string $lastResumedAt,
        public bool $initialized,
        public string $issuer,
        public int $claimMonths,
        public string $label,
        public bool $fundsReached,
        public string $description,
        public string $shortDescription,
        public string $imageUrl,
        public ContractBalanceDtoOutput $contractBalance,
        public string $status,
        public float $goal,
        public float $minPerInvestment,
        public string $returnType,
        public int $returnMonths,
        public string $projectAddress,
        public ?string $muxedAccount = null,
        public ?float $requiredReserveFunds = null
    ) {
    }
}
