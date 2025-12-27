<?php

namespace App\Domain\Crypt;

enum VaultKey: string
{
    case SF_VAULT_SB = 'sf-vault-sb';
    case SF_VAULT_AEAD = 'sf-vault-aead';


    public static function getSbKey(): self
    {
        return self::SF_VAULT_SB;
    }

    public static function getAeadKey(): self
    {
        return self::SF_VAULT_AEAD;
    }
}