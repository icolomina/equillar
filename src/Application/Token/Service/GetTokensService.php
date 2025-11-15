<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\Token\Service;

use App\Application\Token\Transformer\TokenEntityTransformer;
use App\Persistence\Token\TokenStorageInterface;

class GetTokensService
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly TokenEntityTransformer $tokenEntityTransformer,
    ) {
    }

    public function getTokens(): array
    {
        $tokens = $this->tokenStorage->getTokens();

        return $this->tokenEntityTransformer->fromEntitiesToOutputDtos($tokens);
    }
}
