<?php

namespace App\Application\UserContract\Mapper;

use App\Domain\Contract\Investment\UserContractInvestmentStatus;
use App\Domain\I128;
use App\Domain\Utils\Math\I128Handler;
use App\Entity\Investment\UserContractInvestment;

class UserInvestmentTrxResultMapper
{
    public function __construct(
        private readonly I128Handler $i128Handler
    ){}

    public function mapToEntity(array $trxResult, UserContractInvestment $userContractInvestment): void
    {
        $decimals  = $userContractInvestment->getContract()->getToken()->getDecimals();

        foreach($trxResult as $key => $value) {
            $result = match($key) {
                'accumulated_interests', 'deposited', 'total' => I128::fromLoAndHi($value->getLo(), $value->getHi())->toPhp($decimals),
                'claimable_ts' => new \DateTimeImmutable(date('Y-m-d H:i:s', $value)),
                'last_transfer_ts' => ($value > 0) ? new \DateTimeImmutable(date('Y-m-d H:i:s', $value)) : null,
                'paid', 'regular_payment' => I128::fromLoAndHi($value->getLo(), $value->getHi())->toPhp($decimals),
                'status' => UserContractInvestmentStatus::tryFrom($value)?->name ?? UserContractInvestmentStatus::UNKNOWN->name,
                default => null
            };

            $this->setValueToEntity($userContractInvestment, $key, $result);
        }
    }

    private function setValueToEntity(UserContractInvestment $userContractInvestment, string $key, mixed $value): void
    {
        $currentTotalCharged = $userContractInvestment->getTotalCharged() ?? 0; 
        match($key) {
            'accumulated_interests' => $userContractInvestment->setInterests($value), 
            'deposited' => $userContractInvestment->setBalance($value), 
            'total' => $userContractInvestment->setTotal($value),
            'claimable_ts' => $userContractInvestment->setClaimableAt($value),
            'last_transfer_ts' => $userContractInvestment->setLastPaymentReceivedAt($value),
            'paid' => $userContractInvestment->setTotalCharged($currentTotalCharged + $value), 
            'status' => $userContractInvestment->setStatus($value),
            default => null
        };
    }
}
