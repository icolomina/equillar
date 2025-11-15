<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
