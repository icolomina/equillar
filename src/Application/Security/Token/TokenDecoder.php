<?php

namespace App\Application\Security\Token;

use App\Domain\Security\TokenPayload;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class TokenDecoder
{
    public function __construct(
        private readonly DenormalizerInterface $serializer,
        private readonly string $securityTokenKey
    ){}

    public function decode(string $token): TokenPayload
    {
        $decoded = JWT::decode($token, new Key($this->securityTokenKey, 'HS256'));

        /**
         * @var TokenPayload $tokenPayload
         */
        $tokenPayload = $this->serializer->denormalize($decoded, TokenPayload::class);
        return $tokenPayload;
    }
}
