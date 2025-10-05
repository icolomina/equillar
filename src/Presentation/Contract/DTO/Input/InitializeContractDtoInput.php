<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Presentation\Contract\DTO\Input;

readonly class InitializeContractDtoInput
{
    public function __construct(
        public readonly string $projectAddress,
        public readonly int $returnType,
        public readonly int $returnMonths,
        public readonly int $minPerInvestment,
    ) {
    }
}
