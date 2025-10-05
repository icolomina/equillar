<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Presentation\Contract\DTO\Input;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

readonly class CreateContractReserveFundContributionDtoInput
{
    public function __construct(
        #[NotBlank(message: 'Amount cannot be empty')]
        #[GreaterThan(0, message: 'Amount must be greater than 0')]
        public float|int $amount,
    ) {
    }
}
