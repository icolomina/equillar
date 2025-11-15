<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
