<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Security\Authenticator;

use App\Application\Security\Token\TokenDecoder;
use App\Application\Security\Token\TokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class JwtTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly TokenExtractor $tokenExtractor,
        private readonly TokenDecoder $tokenDecoder,
    ) {}

    public function supports(Request $request): ?bool
    {
        return str_contains($request->getUri(), 'api/v1');
    }

    public function authenticate(Request $request): Passport
    {
        $token = $this->tokenExtractor->extract($request->headers);
        if (!$token) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        $userData = $this->tokenDecoder->decode($token);

        return new SelfValidatingPassport(new UserBadge($userData->uuid));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
