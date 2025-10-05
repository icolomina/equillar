<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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
