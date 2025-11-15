<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
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
