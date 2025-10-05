<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Presentation\User\DTO\Input;


use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterUserDtoInput
{
    public function __construct(
        #[NotBlank(message: 'Email cannot be empty')]
        public readonly string $email,

        #[NotBlank(message: 'Name cannot be empty')]
        public readonly string $name,

        #[NotBlank(message: 'Password cannot be empty')]
        public readonly string $password,

        #[NotBlank(message: 'User type cannot be empty')]
        public readonly string $userType,
    ) {
    }
}
