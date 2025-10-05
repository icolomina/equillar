<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractWithdrawalRequestEntityTransformer;
use App\Entity\User;
use App\Persistence\Contract\ContractWithdrawalRequestStorageInterface;
use App\Presentation\Contract\DTO\Output\ContractWithdrawalRequestDtoOutput;

class GetContractWithdrawalRequestsService
{
    public function __construct(
        private readonly ContractWithdrawalRequestStorageInterface $contractWithdrawalRequestStorage,
        private readonly ContractWithdrawalRequestEntityTransformer $contractWithdrawalRequestEntityTransformer,
    ) {
    }

    /**
     * @return ContractWithdrawalRequestDtoOutput[]
     */
    public function getContractRequestWithdrawals(User $user): array
    {
        $withdrawalRequests = ($user->isAdmin())
            ? $this->contractWithdrawalRequestStorage->getAllWithdrawalRequests()
            : $this->contractWithdrawalRequestStorage->getWithdrawalRequestsByUser($user)
        ;

        return $this->contractWithdrawalRequestEntityTransformer->fromEntitiesToOutputDtos($withdrawalRequests);
    }
}
