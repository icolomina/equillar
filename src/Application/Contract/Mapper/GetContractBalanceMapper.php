<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace App\Application\Contract\Mapper;

use App\Domain\I128;
use App\Domain\Utils\Math\I128Handler;
use App\Entity\Contract\ContractBalance;

class GetContractBalanceMapper
{
    public function __construct(
        private readonly I128Handler $i128Handler,
    ) {
    }

    /**
     * @param array<string, I128> $trxResult
     */
    public function mapToEntity(array $trxResult, ContractBalance $contractBalance): void
    {
        $decimals = $contractBalance->getContract()->getToken()->getDecimals();

        foreach ($trxResult as $key => $value) {
            $result = $this->i128Handler->fromI128ToPhpFloat($value->getLo(), $value->getHi(), $decimals);
            match ($key) {
                'reserve' => $contractBalance->setReserveFund($result),
                'project' => $contractBalance->setAvailable($result),
                'comission' => $contractBalance->setComission($result),
                'received_so_far' => $contractBalance->setFundsReceived($result),
                'payments' => $contractBalance->setPayments($result),
                'reserve_contributions' => $contractBalance->setReserveContributions($result),
                'project_withdrawals' => $contractBalance->setProjectWithdrawals($result),
                'moved_from_project_to_reserve' => $contractBalance->setAvailableToReserveMovements($result),
                default => null,
            };
        }
    }
}
