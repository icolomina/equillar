<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Security\Authorization\Voter\Contract;

use App\Domain\Contract\ContractStatus;
use App\Entity\Contract\Contract;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ContractVoter extends Voter
{
    public const APPROVE_CONTRACT  = 'APPROVE_CONTRACT';
    public const ACTIVATE_CONTRACT = 'ACTIVATE_CONTRACT';
    public const MODIFY_CONTRACT   = 'MODIFY_CONTRACT';
    public const EDIT_CONTRACT     = 'EDIT_CONTRACT';
    public const EDIT_CONTRACT_DOCUMENT     = 'EDIT_CONTRACT_DOCUMENT';
    public const PAUSE_CONTRACT = 'PAUSE_CONTRACT';
    public const RESUME_CONTRACT = 'RESUME_CONTRACT';

    public const REQUEST_OPERATION = 'REQUEST_OPERATION';

    public const GET_CONTRACT_RELATED_OPERATIONS = 'GET_CONTRACT_RELATED_OPERATIONS';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof Contract) {
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
            self::APPROVE_CONTRACT  => $this->canApproveContract($user, $subject),
            self::ACTIVATE_CONTRACT => $this->canActivateContract($user, $subject),
            self::MODIFY_CONTRACT   => $this->canModifyContract($user, $subject),
            self::EDIT_CONTRACT,self::EDIT_CONTRACT_DOCUMENT     => $this->canEditContract($user, $subject),
            self::GET_CONTRACT_RELATED_OPERATIONS => $this->canGetContractRelatedOperations($user, $subject),
            self::REQUEST_OPERATION => $this->canRequestContractOperation($user, $subject),
            self::RESUME_CONTRACT => $this->canResumeContract($user, $subject),
            self::PAUSE_CONTRACT => $this->canPauseContract($user, $subject),
            default => true
        };
    }

    private function canApproveContract(User $user, Contract $contract): bool
    {
        return $user->isAdmin() && $contract->getStatus() === ContractStatus::REVIEWING->name;
    }

    private function canActivateContract(User $user, Contract $contract): bool
    {
        return ($user->isAdmin() || ($user->isCompany() && $contract->getIssuer()->isEqualTo($user))) && $contract->getStatus() === ContractStatus::APPROVED->name;
    }

    private function canModifyContract(User $user, Contract $contract): bool
    {
        return ($user->isAdmin() || ($user->isCompany() && $contract->getIssuer()->isEqualTo($user))) && in_array($contract->getStatus(), [ContractStatus::REVIEWING->name, ContractStatus::APPROVED->name]);
    }

    private function canEditContract(User $user, Contract $contract): bool
    {
        return $this->checkValidCompanyContractIssuer($user, $contract);
    }

    private function canRequestContractOperation(User $user, Contract $contract): bool
    {
        return ($user->isAdmin() || ($user->isCompany() && $contract->getIssuer()->isEqualTo($user))) && in_array($contract->getStatus(), [ContractStatus::ACTIVE->name, ContractStatus::FUNDS_REACHED->name]);
    }

    private function canResumeContract(User $user, Contract $contract): bool
    {
        return ($user->isAdmin() || ($user->isCompany() && $contract->getIssuer()->isEqualTo($user))) && $contract->getStatus() === ContractStatus::PAUSED->name;
    }

    private function canPauseContract(User $user, Contract $contract): bool
    {
        return ($user->isAdmin() || ($user->isCompany() && $contract->getIssuer()->isEqualTo($user))) && $contract->getStatus() === ContractStatus::ACTIVE->name;
    }

    private function canGetContractRelatedOperations(User $user, Contract $contract): bool
    {
        return $this->checkValidCompanyContractIssuer($user, $contract);
    }

    private function checkValidCompanyContractIssuer(User $user, Contract $contract): bool
    {
        if($user->isCompany()) {
            return $contract->getIssuer()->isEqualTo($user);
        }

        return true;
    }
}
