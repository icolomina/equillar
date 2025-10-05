<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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
