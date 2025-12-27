<?php

namespace App\Domain\Crypt\Service;

use App\Domain\Crypt\CryptKey;
use App\Domain\Crypt\VaultKey;

class Vault
{
    public function __construct(
        private readonly array $vaultKeys
    ){}

    public function getAeadKey(): CryptKey
    {
        $aeadKey = VaultKey::getAeadKey()->value;
        if(!isset($this->vaultKeys[$aeadKey])) {
            throw new \RuntimeException('Aead vault key not configured');
        }

        return new CryptKey(
            $aeadKey,
            $this->vaultKeys[$aeadKey]
        );
    } 
    
    public function getSbKey(): CryptKey
    {
        $sbKey = VaultKey::getSbKey()->value;
        if(!isset($this->vaultKeys[$sbKey])) {
            throw new \RuntimeException('SecretBox vault key not configured');
        }

        return new CryptKey(
            $sbKey,
            $this->vaultKeys[$sbKey]
        );
    }
}