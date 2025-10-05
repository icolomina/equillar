<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Presentation\Contract\DTO\Output;


readonly class ContractWithdrawalRequestDtoOutput
{
    public function __construct(
        public int $id,
        public string $contractLabel,
        public string $requestedAt,
        public string $requestedBy,
        public string $requestedAmount,
        public ?string $status,
        public ?string $approvedAt = null,
        public ?string $hash = null,
    ) {
    }
}
