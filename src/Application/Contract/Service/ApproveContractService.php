<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Entity\Contract\Contract;
use App\Persistence\PersistorInterface;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;

class ApproveContractService
{
    public function __construct(
        private readonly ContractEntityTransformer $contractEntityTransformer,
        private readonly PersistorInterface $persistor,
    ) {
    }

    public function approveContract(Contract $contract): ContractDtoOutput
    {
        $this->contractEntityTransformer->updateContractAsApproved($contract);
        $this->persistor->persistAndFlush($contract);

        return $this->contractEntityTransformer->fromEntityToOutputDto($contract);
    }
}
