<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\Contract\DTO\Input;

class CreateUserContractDtoInput
{
    public function __construct(
        public readonly string $contractAddress,
        public readonly string $hash,
        public readonly string $deposited,
        public readonly string $status,
        public readonly string $fromAddress,
    ) {
    }
}
