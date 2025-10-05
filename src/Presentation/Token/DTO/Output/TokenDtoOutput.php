<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Presentation\Token\DTO\Output;


class TokenDtoOutput
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $code,
        public readonly string $address,
        public readonly string $createdAt,
        public readonly bool $enabled,
        public readonly string $issuer,
        public readonly int $decimals,
        public readonly ?string $locale = null,
        public readonly ?string $fiatReference = null,
    ) {
    }
}
