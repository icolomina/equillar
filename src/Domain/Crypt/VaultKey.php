<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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