<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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
