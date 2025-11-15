<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\Token\Doctrine;

use App\Entity\Token;
use App\Persistence\Layers\Doctrine\AbstractDoctrineStorage;
use App\Persistence\Token\TokenStorageInterface;

class TokenDoctrineStorage extends AbstractDoctrineStorage implements TokenStorageInterface
{
    public function getTokens(): array
    {
        return $this->em->getRepository(Token::class)->findAll();
    }

    public function getOneByCode(string $code): ?Token
    {
        return $this->em->getRepository(Token::class)->findOneBy(['code' => $code]);
    }

    public function createToken(string $tokenAddress, string $tokenName, string $tokenSymbol, int $tokenDecimals, string $tokenIssuer): ?Token
    {
        $token = new Token();
        $token->setAddress($tokenAddress);
        $token->setName($tokenName);
        $token->setCode($tokenSymbol);
        $token->setDecimals($tokenDecimals);
        $token->setIssuer($tokenIssuer);
        $token->setEnabled(true);
        $token->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($token);
        $this->em->flush();

        return $token;
    }
}
