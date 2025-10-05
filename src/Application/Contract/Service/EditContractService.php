<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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
