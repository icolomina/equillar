<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Security\Authorization\Voter\Contract;

use App\Domain\Contract\ContractReserveFundContributionStatus;
use App\Entity\Contract\ContractReserveFundContribution;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\User;

class ContractReserveFundContributionVoter extends Voter
{
    public const CHECK_RESERVE_FUND_CONTRIBUTION = 'CHECK_RESERVE_FUND_CONTRIBUTION';
    public const TRANSFER_RECEIVE_FUND_CONTRIBUTION  = 'TRANSFER_RECEIVE_FUND_CONTRIBUTION';


    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($subject instanceof ContractReserveFundContribution && in_array($attribute, [self::CHECK_RESERVE_FUND_CONTRIBUTION, self::TRANSFER_RECEIVE_FUND_CONTRIBUTION] )) {
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

        return match($attribute) {
            self::CHECK_RESERVE_FUND_CONTRIBUTION => ($user->isAdmin() || ($user->isCompany() && $subject->getContract()->getIssuer()->isEqualTo($user))) && $subject->getStatus() === ContractReserveFundContributionStatus::CREATED->name,
            self::TRANSFER_RECEIVE_FUND_CONTRIBUTION => $user->isAdmin() && $subject->getStatus() === ContractReserveFundContributionStatus::RECEIVED->name,
            default => true
        };
    }
}
