<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace App\Application\Contract\Service;

use App\Application\ContractCode\Transformer\ContractCodeEntityTransformer;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\DeployContractOperation;
use App\Persistence\PersistorInterface;
use Symfony\Component\Filesystem\Filesystem;

class DeployContractService
{
    public function __construct(
        private readonly DeployContractOperation $deployContractOperation,
        private readonly ContractCodeEntityTransformer $contractCodeEntityTransformer,
        private readonly PersistorInterface $persistor,
    ) {
    }

    public function deployContract(string $wasmFile, string $status, string $version, ?string $comments): string
    {
        $wasmContent = (new Filesystem())->readFile($wasmFile);
        $wasmId = $this->deployContractOperation->deploy($wasmContent);

        $contractCode = $this->contractCodeEntityTransformer->fromDeployedWasmToContractCode($wasmId, $status, $version, $comments);
        $this->persistor->persistAndFlush($contractCode);

        return $wasmId;
    }
}
