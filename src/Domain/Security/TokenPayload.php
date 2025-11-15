<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Security;

readonly class TokenPayload
{
    public function __construct(
        public string $iss,
        public string $aud,
        public string $iat,
        public string $nbf,
        public string $exp,
        public string $uuid,
    ) {
    }
}
