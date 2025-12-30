<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Crypt\SecretBox;

readonly class SecretBoxCryptedValue
{
    public function __construct(
        public string $cipher,
        public string $nonce,
        public string $engine
    ) {
    }
}
