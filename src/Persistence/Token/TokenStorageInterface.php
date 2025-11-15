<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\Token;

use App\Entity\Token;

interface TokenStorageInterface
{
    public function getTokens(): array;

    public function getOneByCode(string $code): ?Token;

    public function createToken(string $tokenAddress, string $tokenName, string $tokenSymbol, int $tokenDecimals, string $tokenIssuer): ?Token;
}
