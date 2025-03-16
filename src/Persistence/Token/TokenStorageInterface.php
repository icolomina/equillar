<?php

namespace App\Persistence\Token;

use App\Entity\Token;

interface TokenStorageInterface
{
    public function getTokens(): array;
    public function getOneByCode(string $code): ?Token;
    public function createToken(string $tokenAddress, string $tokenName, string $tokenSymbol, int $tokenDecimals, string $tokenIssuer): ?Token;
}
