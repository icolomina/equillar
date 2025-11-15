<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
