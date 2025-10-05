<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\Contract\Service;

use Symfony\Component\Uid\Uuid;

class ContractReserveFundContributonIdEncoder
{
    public function encodeId(string $uuid): string
    {
        if (!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException('Invalid Uuid');
        }

        return Uuid::fromString($uuid)->toBase32();
    }

    public function decodeId(string $id): string
    {
        return Uuid::fromBase32($id);
    }
}
