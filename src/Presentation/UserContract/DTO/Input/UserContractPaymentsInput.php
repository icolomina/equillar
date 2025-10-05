<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Presentation\UserContract\DTO\Input;


use Symfony\Component\Validator\Constraints\Date;

readonly class UserContractPaymentsInput
{
    public function __construct(
        #[Date(message: 'From must be a valid date')]
        public ?string $from = null,
        #[Date(message: 'To must be a valid date')]
        public ?string $to = null,
        public ?string $status = null,
        public ?int $projectId = null,
    ) {
    }
}
