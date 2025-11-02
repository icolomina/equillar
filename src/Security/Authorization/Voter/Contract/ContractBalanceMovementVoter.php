<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Security\Authorization\Voter\Contract;

use App\Entity\Contract\ContractBalanceMovement;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\User;

class ContractBalanceMovementVoter extends Voter
{
    public const PERFORM_BALANCE_MOVEMENT = 'PERFORM_BALANCE_MOVEMENT';


    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($subject instanceof ContractBalanceMovement && in_array($attribute, [self::PERFORM_BALANCE_MOVEMENT] )) {
            return true;
        }

        return false;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var User
         */
        $user = $token->getUser();
        return $user->isAdmin() && $subject->getStatus() === 'CREATED';
    }
}
