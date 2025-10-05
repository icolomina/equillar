<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Presentation\Contract\DTO\Output;


use App\Presentation\Token\DTO\Output\TokenContractDtoOutput;

class ContractDtoOutput
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $address,
        public readonly TokenContractDtoOutput $tokenContract,
        public readonly float $rate,
        public readonly string $createdAt,
        public readonly ?string $initializedAt,
        public readonly ?string $approvedAt,
        public readonly ?string $lastPausedAt,
        public readonly ?string $lastResumedAt,
        public readonly bool $initialized,
        public readonly string $issuer,
        public readonly int $claimMonths,
        public readonly string $label,
        public readonly bool $fundsReached,
        public readonly string $description,
        public readonly string $shortDescription,
        public readonly string $imageUrl,
        public readonly ContractBalanceDtoOutput $contractBalance,
        public readonly string $status,
        public readonly float $goal,
        public readonly float $minPerInvestment,
        public readonly string $returnType,
        public readonly int $returnMonths,
        public readonly string $projectAddress,
    ) {
    }
}
