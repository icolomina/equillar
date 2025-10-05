<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Security\Authorization\Voter\Contract\User;

use App\Entity\Contract\UserContract;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\User;

class UserContractVoter extends Voter
{
    public const GET_USER_CONTRACT  = 'GET_USER_CONTRACT';
    public const EDIT_USER_CONTRACT = 'EDIT_USER_CONTRACT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof UserContract && in_array($attribute, [self::EDIT_USER_CONTRACT, self::GET_USER_CONTRACT])) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var User $user
         */
        $user = $token->getUser();
        return $user->isSaver() && $subject->getUser()->isEqualTo($user);
    }
}
