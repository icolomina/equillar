<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
