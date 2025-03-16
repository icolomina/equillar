<?php

namespace App\Application\Security\Token;

use Symfony\Component\HttpFoundation\HeaderBag;

class TokenExtractor
{
    public function extract(HeaderBag $headers): ?string
    {
        if($headers->has('Authorization')) {
            return trim(str_replace('Bearer', '', $headers->get('Authorization')));
        }

        return null;
    }
}
