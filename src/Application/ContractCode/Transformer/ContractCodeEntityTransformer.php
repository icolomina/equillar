<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\ContractCode\Transformer;

use App\Entity\ContractCode;

class ContractCodeEntityTransformer
{
    public function fromDeployedWasmToContractCode(string $wasmId, string $status, string $version, ?string $comments): ContractCode
    {
        $contractCode = new ContractCode();
        $contractCode->setWasmId($wasmId);
        $contractCode->setStatus($status);
        $contractCode->setCreatedAt(new \DateTimeImmutable());
        $contractCode->setVersion($version);
        $contractCode->setComments($comments);

        return $contractCode;
    }
}
