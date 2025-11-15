<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
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
