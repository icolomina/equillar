<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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
