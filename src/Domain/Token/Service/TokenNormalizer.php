<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\Token\Service;

use App\Domain\I128;

class TokenNormalizer
{
    public function normalizeTokenValue(float|int $value, int $tokenDecimals): I128
    {
        $valueStr = (string) $value;
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
