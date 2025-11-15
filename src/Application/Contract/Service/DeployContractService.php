<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
