<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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