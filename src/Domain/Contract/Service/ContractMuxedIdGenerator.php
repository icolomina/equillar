<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Contract\Service;

use App\Entity\Contract\Contract;

class ContractMuxedIdGenerator
{
    private const INT4_MIN = 1;
    private const INT4_MAX = 2147483647;

    public function generateMuxedId(Contract $contract): int
    {
        $orgId        = $contract->getOrganzation()->getId();
        $contractId   = $contract->getId();

        $muxedId = $orgId * $contractId;

        if( $muxedId < self::INT4_MIN) {
            throw new \RuntimeException('ID ' . $muxedId . ' out of range. Range allowed from 1 to ' . self::INT4_MAX . ' which is the PostgreSQL int max alowed');
        }

        if ($muxedId > self::INT4_MAX) {
            throw new \RuntimeException('ID ' . $muxedId . ' out of range. Range allowed from 1 to ' . self::INT4_MAX . ' which is the PostgreSQL int max alowed');
        }

        return  $muxedId;
    }
}
