<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\Security\Token;

use Symfony\Component\HttpFoundation\HeaderBag;

class TokenExtractor
{
    public function extract(HeaderBag $headers): ?string
    {
        if ($headers->has('Authorization')) {
            return trim(str_replace('Bearer', '', $headers->get('Authorization')));
        }

        return null;
    }
}
