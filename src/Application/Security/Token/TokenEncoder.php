<?php

namespace App\Application\Security\Token;

use App\Domain\Security\TokenPayloadBuilder;
use App\Entity\User;
use Firebase\JWT\JWT;
use Symfony\Component\Serializer\SerializerInterface;

class TokenEncoder
{
    public function __construct(
        private readonly TokenPayloadBuilder $tokenPayloadBuilder,
        private readonly SerializerInterface $serializer,
        private readonly string $securityTokenKey
    ){}

    public function encode(User $user): string
    {
        $payload = $this->tokenPayloadBuilder->build($user->getUserIdentifier());
        return JWT::encode($this->serializer->normalize($payload), $this->securityTokenKey, 'HS256');
    }
}
