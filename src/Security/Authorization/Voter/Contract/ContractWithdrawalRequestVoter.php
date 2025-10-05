<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Security\Authorization\Voter\Contract;

use App\Domain\Contract\ContractWithdrawalStatus;
use App\Entity\Contract\ContractWithdrawalRequest;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\User;

class ContractWithdrawalRequestVoter extends Voter
{
    public const APPROVE_WITHDRAWAL_REQUEST = 'APPROVE_WITHDRAWAL_REQUEST';
    public const REJECT_WITHDRAWAL_REQUEST  = 'REJECT_WITHDRAWAL_REQUEST';


    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof ContractWithdrawalRequest && in_array($attribute, [self::APPROVE_WITHDRAWAL_REQUEST, self::REJECT_WITHDRAWAL_REQUEST] )) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var User
         */
        $user = $token->getUser();
        return $user->isAdmin() && $subject->getStatus() === ContractWithdrawalStatus::REQUESTED->name;
    }
}
