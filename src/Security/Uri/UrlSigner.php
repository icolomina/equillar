<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Security\Uri;

use Symfony\Component\DependencyInjection\Attribute\Lazy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\UriSigner;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Lazy]
class UrlSigner
{
    private UriSigner $uriSigner;

    public function __construct(
        private readonly string $uriSignerKey,
    ) {
        $this->uriSigner = new UriSigner($this->uriSignerKey);
    }

    public function signUrl(string $url): string
    {
        return $this->uriSigner->sign($url);
    }

    public function check(string|Request $request): bool
    {
        return ($request instanceof Request)
            ? $this->uriSigner->checkRequest($request)
            : $this->uriSigner->check($request)
        ;
    }

    public function validateRequestSignature(Request $request): void
    {
        if (!$this->check($request)) {
            throw new AccessDeniedException('Invalid request signature');
        }
    }
}
