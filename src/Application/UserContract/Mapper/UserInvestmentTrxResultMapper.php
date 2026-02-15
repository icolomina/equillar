<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\UserContract\Mapper;

use App\Domain\I128;
use App\Domain\UserContract\UserContractStatus;
use App\Entity\Contract\UserContract;

class UserInvestmentTrxResultMapper
{
    public function mapToEntity(array $trxResult, UserContract $userContract): void
    {
        $decimals = $userContract->getContract()->getToken()->getDecimals();

        foreach ($trxResult as $key => $value) {
            $result = match ($key) {
                'accumulated_interests', 'deposited', 'total', 'paid', 'regular_payment','commission' => I128::fromLoAndHi($value->getLo(), $value->getHi())->toPhp($decimals),
                'claimable_ts', 'token_id' => $value,
                'last_transfer_ts' => ($value > 0) ? new \DateTimeImmutable(date('Y-m-d H:i:s', $value)) : null,
                'status' => (UserContractStatus::tryFrom($value) ?? UserContractStatus::UNKNOWN)->name,
                default => null,
            };

            $this->setValueToEntity($userContract, $key, $result);
        }
    }

    private function setValueToEntity(UserContract $userContract, string $key, mixed $value): void
    {
        $currentTotalCharged = $userContract->getTotalCharged() ?? 0;
        match ($key) {
            'accumulated_interests' => $userContract->setInterests($value),
            'commission' => $userContract->setCommission($value),
            'deposited' => $userContract->setBalance($value),
            'total' => $userContract->setTotal($value),
            'claimable_ts' => $userContract->setClaimableTs($value),
            'last_transfer_ts' => $userContract->setLastPaymentReceivedAt($value),
            'paid' => $userContract->setTotalCharged($currentTotalCharged + $value),
            'status' => $userContract->setStatus($value),
            'regular_payment' => $userContract->setRegularPayment($value),
            'token_id' => $userContract->setTokenId($value),
            default => null,
        };
    }
}
