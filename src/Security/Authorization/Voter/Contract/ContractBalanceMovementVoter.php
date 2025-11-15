<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
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
