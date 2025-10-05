<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace App\Application\UserContract\Transformer;

use App\Domain\User\UserContractPaymentStatus;
use App\Entity\Contract\UserContract;
use App\Entity\Contract\UserContractPayment;
use App\Presentation\UserContract\DTO\Output\UserContractPaymentDtoOutput;

class UserContractPaymentEntityTransformer
{
    public function createFromPayableUserContract(UserContract $userContract): UserContractPayment
    {
        $userContractPayment = new UserContractPayment();
        $userContractPayment->setUserContract($userContract);
        $userContractPayment->setCreatedAt(new \DateTimeImmutable());
        $userContractPayment->setStatus(UserContractPaymentStatus::SENT->name);

        return $userContractPayment;
    }

    public function updatePaymentWithSuccessfulTransactionResult(UserContractPayment $userContractPayment, string $trxHash, float $totalClaimed, \DateTimeImmutable $paidAt): void
    {
        $userContractPayment->setPaidAt($paidAt);
        $userContractPayment->setTotalClaimed($totalClaimed);
        $userContractPayment->setHash($trxHash);
        $userContractPayment->setStatus(UserContractPaymentStatus::CONFIRMED->name);
    }

    public function fromEntityToOutputDto(UserContractPayment $userContractPayment): UserContractPaymentDtoOutput
    {
        $token = $userContractPayment->getUserContract()->getContract()->getToken();
        $numberFormatter = new \NumberFormatter($token->getLocale(), \NumberFormatter::CURRENCY);

        $outputDto = new UserContractPaymentDtoOutput(
            (string) $userContractPayment->getId(),
            $userContractPayment->getUserContract()->getContract()->getIssuer()->getName(),
            $userContractPayment->getUserContract()->getContract()->getLabel(),
            $userContractPayment->getHash(),
            $userContractPayment->getCreatedAt()->format('Y-m-d H:i'),
            $numberFormatter->formatCurrency($userContractPayment->getTotalClaimed(), $token->getReferencedCurrency()),
            $userContractPayment->getStatus(),
            $userContractPayment->getPaidAt()?->format('Y-m-d H:i')
        );

        if ($userContractPayment->getPaidAt()) {
            $outputDto->totalReceived = $numberFormatter->formatCurrency($userContractPayment->getTotalClaimed(), $token->getReferencedCurrency());
        }

        return $outputDto;
    }

    /**
     * @param UserContractPayment[] $userContractPayments
     *
     * @return UserContractPaymentDtoOutput[]
     */
    public function fromEntitiesToOutputDtos(array $userContractPayments): array
    {
        return array_map(
            fn (UserContractPayment $userContractPayment) => $this->fromEntityToOutputDto($userContractPayment),
            $userContractPayments
        );
    }
}
