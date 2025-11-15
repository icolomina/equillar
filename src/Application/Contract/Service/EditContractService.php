<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Entity\Contract\Contract;
use App\Persistence\Contract\ContractBalanceStorageInterface;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;

class EditContractService
{
    public function __construct(
        private readonly ContractBalanceStorageInterface $contractInvestmentBalanceStorage,
        private readonly ContractEntityTransformer $contractEntityTransformer,
    ) {
    }

    public function editContract(Contract $contract): ContractDtoOutput
    {
        $contractBalance = ($contract->isInitialized())
            ? $this->contractInvestmentBalanceStorage->getLastSuccesfulBalanceByContract($contract)
            : null
        ;

        return $this->contractEntityTransformer->fromEntityToOutputDto($contract, $contractBalance);
    }

    public function editContractWithoutBalance(Contract $contract): ContractDtoOutput
    {
        return $this->contractEntityTransformer->fromEntityToOutputDto($contract, null);
    }
}
