<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace App\Application\UserContract\Transformer;

use App\Application\Token\Transformer\TokenEntityTransformer;
use App\Domain\Contract\ContractReturnType;
use App\Domain\UserContract\UserContractPaymentCalendarItem;
use App\Entity\Contract\Contract;
use App\Entity\Contract\UserContract;
use App\Entity\UserWallet;
use App\Presentation\Contract\DTO\Input\CreateUserContractDtoInput;
use App\Presentation\UserContract\DTO\Output\UserContractDtoOutput;

readonly class UserContractEntityTransformer
{
    public function __construct(
        private readonly TokenEntityTransformer $tokenEntityTransformer,
    ) {
    }

    /**
     * @param UserContractPaymentCalendarItem[] $calendar
     */
    public function fromEntityToOutputDto(UserContract $userContract, array $calendar = []): UserContractDtoOutput
    {
        $claimableDate = ($userContract->getClaimableTs() > 0) ? date('Y-m-d H:i', $userContract->getClaimableTs()) : 'Unknown yet';
        $tokenContract = $this->tokenEntityTransformer->fromEntityToContractTokenOutputDto($userContract->getContract()->getToken());

        return new UserContractDtoOutput(
            (string) $userContract->getId(),
            $userContract->getContract()->getIssuer()->getName(),
            $userContract->getContract()->getLabel(),
            $userContract->getContract()->getAddress(),
            $tokenContract,
            $userContract->getContract()->getRate(),
            $userContract->getCreatedAt()->format('Y-m-d H:i'),
            $claimableDate,
            $userContract->getBalance(),
            $userContract->getInterests(),
            $userContract->getCommission(),
            $userContract->getTotal(),
            $userContract->getHash(),
            $userContract->getStatus(),
            ContractReturnType::tryFrom($userContract->getContract()->getReturnType())->getReadableName(),
            $calendar
        );
    }

    /**
     * @return UserContractDtoOutput[]
     */
    public function fromEntitiesToOutputDtos(array $userContracts): array
    {
        return array_map(
            fn (UserContract $userContract) => $this->fromEntityToOutputDto($userContract),
            $userContracts
        );
    }

    public function fromCreateUserContractInvestmentDtoToEntity(CreateUserContractDtoInput $createUserContractDtoInput, Contract $contract, UserWallet $userWallet): UserContract
    {
        $userContract = new UserContract();
        $userContract->setUsr($userWallet->getUsr());
        $userContract->setContract($contract);
        $userContract->setBalance((float) $createUserContractDtoInput->deposited);
        $userContract->setHash($createUserContractDtoInput->hash);
        $userContract->setCreatedAt(new \DateTimeImmutable());
        $userContract->setUserWallet($userWallet);
        $userContract->setStatus($createUserContractDtoInput->status);
        $userContract->setClaimableTs(0);

        return $userContract;
    }

    public function updateUserContractWithNewClaim(UserContract $userContract, \DateTimeImmutable $transferredAt): void
    {
        $currentTotalCharged = $userContract->getTotalCharged() ?? 0;

        $userContract->setLastPaymentReceivedAt($transferredAt);
        $userContract->setTotalCharged($currentTotalCharged + $userContract->getRegularPayment());
    }
}
