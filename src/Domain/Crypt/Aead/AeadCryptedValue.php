<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Crypt\Aead;

final readonly class AeadCryptedValue
{
    public function __construct(
        public string $ciphertext,
        public string $nonce,
        public string $schema,
        public string $version,
        public string $engine,
        public string $keyId,
        public string $context,
        public int $subkeyId,
    ) {
    }
}