<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\Security;

class TokenPayloadBuilder
{
    public function build(string $userIdentifier): TokenPayload
    {
        $now = new \DateTimeImmutable();
        $plus1hour = (new \DateTime())->add(\DateInterval::createFromDateString('+ 1 hour'));

        return new TokenPayload(
            'https://x.app',
            'AUTHENTICATHED_USERS',
            (string) $now->getTimestamp(),
            (string) $now->getTimestamp(),
            (string) $plus1hour->getTimestamp(),
            $userIdentifier
        );
    }
}
