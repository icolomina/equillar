<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace App\Application\Security\Token;

use App\Domain\Security\TokenPayloadBuilder;
use App\Entity\User;
use Firebase\JWT\JWT;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TokenEncoder
{
    public function __construct(
        private readonly TokenPayloadBuilder $tokenPayloadBuilder,
        private readonly NormalizerInterface $serializer,
        private readonly string $securityTokenKey,
    ) {
    }

    public function encode(User $user): string
    {
        $payload = $this->tokenPayloadBuilder->build($user->getUserIdentifier());

        return JWT::encode($this->serializer->normalize($payload), $this->securityTokenKey, 'HS256');
    }
}
