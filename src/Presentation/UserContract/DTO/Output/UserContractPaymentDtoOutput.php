<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
