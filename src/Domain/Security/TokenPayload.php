<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
