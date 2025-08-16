<?php

namespace App\Security\Authorization\Voter\Contract;

use App\Entity\Contract\ContractWithdrawalRequest;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\User;

class ConfirmWithdrawalRequestVoter extends Voter
{

    public const CONFIRM_WITHDRAWAL_REQUEST = 'CONFIRM_WITHDRAWAL_REQUEST';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::CONFIRM_WITHDRAWAL_REQUEST && $subject instanceof ContractWithdrawalRequest;
    }

    
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var User
         */
        $user = $token->getUser();
        return $user->isEqualTo($subject->getContract()->getUser());
    }
}
