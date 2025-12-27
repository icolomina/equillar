<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
        private readonly string $appSecret,
    ) {
    }

    public function encode(User $user): string
    {
        $payload = $this->tokenPayloadBuilder->build($user->getUserIdentifier());

        return JWT::encode($this->serializer->normalize($payload), $this->appSecret, 'HS256');
    }
}
