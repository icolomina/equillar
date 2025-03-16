<?php

namespace App\Domain\Security;

class TokenPayloadBuilder
{
    public function build(string $userIdentifier): TokenPayload
    {
        $now       = new \DateTimeImmutable();
        $plus1hour = (new \DateTime())->add(\DateInterval::createFromDateString('+ 1 hour'));

        return new TokenPayload(
            'https://x.app',
            'AUTHENTICATHED_USERS',
            $now->getTimestamp(),
            $now->getTimestamp(),
            $plus1hour->getTimestamp(),
            $userIdentifier
        );
    }
}
