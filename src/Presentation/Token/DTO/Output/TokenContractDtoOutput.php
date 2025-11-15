<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\Token\DTO\Output;


readonly class TokenContractDtoOutput
{
    public function __construct(
        public string $name,
        public string $code,
        public string $issuer,
        public int $decimals,
        public ?string $locale = null,
        public ?string $fiatReference = null,
    ) {
    }
}
