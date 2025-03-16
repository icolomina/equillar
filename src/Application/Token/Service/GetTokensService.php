<?php

namespace App\Application\Token\Service;

use App\Application\Token\Transformer\TokenEntityTransformer;
use App\Persistence\Token\TokenStorageInterface;

class GetTokensService
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly TokenEntityTransformer $tokenEntityTransformer
    ){}

    public function getTokens(): array
    {
        $tokens = $this->tokenStorage->getTokens();
        return $this->tokenEntityTransformer->fromEntitiesToOutputDtos($tokens);
    }
}
