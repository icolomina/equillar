<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Presentation\Contract\DTO\Output;


readonly class ContractEditDtoOutput
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $address,
        public readonly string $token,
        public readonly int $tokenDecimals,
        public readonly string $tokenCode,
        public readonly float $rate,
        public readonly string $createdAt,
        public readonly ?string $initializedAt,
        public readonly bool $initialized,
        public readonly string $issuer,
        public readonly int $claimMonths,
        public readonly string $label,
        public readonly bool $fundsReached,
        public readonly string $description,
        public readonly string $shortDescription,
        public ContractBalanceDtoOutput $contractBalance,
        public readonly string $status,
        public readonly string $goal,
        public readonly float $minPerInvestment,
        public readonly string $returnType,
        public readonly int $returnMonths,
    ) {
    }
}
