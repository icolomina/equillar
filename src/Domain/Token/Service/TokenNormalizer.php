<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Token\Service;

use App\Domain\I128;

class TokenNormalizer
{
    public function normalizeTokenValue(string|float|int $value, int $tokenDecimals): I128
    {
        $valueStr = !is_string($value) ? (string) $value : $value;
        $fraction = '';
        $whole = $valueStr;

        if (str_contains($valueStr, '.')) {
            list($whole, $fraction) = explode('.', $valueStr);
        }

        if (strlen($fraction) > $tokenDecimals) {
            $fraction = substr($fraction, 0, $tokenDecimals);
        } elseif (strlen($fraction) < $tokenDecimals) {
            $fraction = str_pad($fraction, $tokenDecimals, '0');
        }

        $valueStr = $whole.$fraction;
        if (1 === bccomp($valueStr, (string) PHP_INT_MAX, 0)) {
            throw new \OverflowException(sprintf('Max PHP size (%s) for integer numbers exceeded: %s', PHP_INT_MAX, $valueStr));
        }

        return new I128((int) $valueStr);
    }
}
