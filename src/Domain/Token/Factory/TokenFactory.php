<?php

namespace App\Domain\Token\Factory;

use App\Domain\Token\Token;

class TokenFactory
{
    public function createToken(string $name, string $code, string $address, int $decimals): Token
    {
        $token = new Token();
        $token->setName($name);
        $token->setCode($code);
        $token->setAddress($address);
        $token->setDecimals($decimals);

        return $token;
    }
}
