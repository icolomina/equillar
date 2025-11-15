<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
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
