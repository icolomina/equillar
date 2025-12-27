<?php

namespace App\Domain\Crypt;

enum CryptEngine: string
{
    case AEAD = 'aead';
    case SECRET_BOX = 'secret_box';

    public static function getDefaultEngine(): self
    {
        return self::AEAD;
    }
}