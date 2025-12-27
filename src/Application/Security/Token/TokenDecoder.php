<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\Security\Token;

use App\Domain\Security\TokenPayload;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class TokenDecoder
{
    public function __construct(
        private readonly DenormalizerInterface $serializer,
        private readonly string $appSecret,
    ) {
    }

    public function decode(string $token): TokenPayload
    {
        $decoded = JWT::decode($token, new Key($this->appSecret, 'HS256'));

        /**
         * @var TokenPayload $tokenPayload
         */
        $tokenPayload = $this->serializer->denormalize($decoded, TokenPayload::class);

        return $tokenPayload;
    }
}
