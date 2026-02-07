<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Contract;

enum ContractError: int
{
    case AddressInsufficientBalance = 1;
    case ContractInsufficientBalance = 2;
    case AmountLessThanMinimum = 5;
    case InterestRateMustBeGreaterThanZero = 6;
    case GoalMustBeGreaterThanZero = 7;
    case UnsupportedReturnType = 8;
    case ReturnMonthsMustBeGreaterThanZero = 9;
    case MinPerInvestmentMustBeGreaterThanZero = 10;
    case AddressHasNotInvested = 14;
    case AddressInvestmentIsNotClaimableYet = 15;
    case AddressInvestmentIsFinished = 16;
    case AddressInvestmentNextTransferNotClaimableYet = 17;
    case ProjectBalanceInsufficientAmount = 24;
    case RecipientCannotReceivePayment = 28;
    case InvalidPaymentData = 29;
    case WouldExceedGoal = 30;
    case GoalAlreadyReached = 31;
    case AmountToInvestMustBeGreaterThanZero = 32;

    case EnforcedPause = 1000;  // OpenZeppelin Pausable error

    public function getMessage(): string
    {
        return match ($this) {
            self::AddressInsufficientBalance => 'Address has insufficient balance to perform this operation',
            self::ContractInsufficientBalance => 'Contract has insufficient balance to complete this transaction',
            self::AmountLessThanMinimum => 'Amount is less than the minimum required',
            self::InterestRateMustBeGreaterThanZero => 'Interest rate must be greater than zero',
            self::GoalMustBeGreaterThanZero => 'Goal must be greater than zero',
            self::UnsupportedReturnType => 'The return type specified is not supported',
            self::ReturnMonthsMustBeGreaterThanZero => 'Return months must be greater than zero',
            self::MinPerInvestmentMustBeGreaterThanZero => 'Minimum per investment must be greater than zero',
            self::AddressHasNotInvested => 'This address has not made any investment in the project',
            self::AddressInvestmentIsNotClaimableYet => 'Investment is not yet available to be claimed',
            self::AddressInvestmentIsFinished => 'Investment has finished and no more operations can be performed',
            self::AddressInvestmentNextTransferNotClaimableYet => 'Next investment transfer is not yet available to be claimed',
            self::ProjectBalanceInsufficientAmount => 'Project does not have sufficient balance to perform this operation',
            self::RecipientCannotReceivePayment => 'The recipient address cannot receive payments in the established contract asset. Maybe the asset trustline has not been created yet.',
            self::InvalidPaymentData => 'The payment data provided is invalid',
            self::WouldExceedGoal => 'The investment would exceed the project goal',
            self::GoalAlreadyReached => 'The project goal has already been reached',
            self::AmountToInvestMustBeGreaterThanZero => 'The amount to invest must be greater than zero',
            self::EnforcedPause => 'The contract is currently paused and cannot perform this operation',
            default => 'Unknown error',
        };
    }

    /**
     * Extract and return a ContractError from a raw Soroban error string
     * Soroban returns contract errors in the format: "HostError: Error(Contract, #<error_code>)"
     * 
     * @param string $rawError The raw error string from Soroban
     * @return self|null The corresponding ContractError case or null if not found
     */
    public static function fromRawError(string $rawError): ?self
    {
        // Pattern to match: "HostError: Error(Contract, #<number>)"
        if (preg_match('#Error\(Contract,\s*\#(\d+)\)#', $rawError, $matches)) {
            $errorCode = (int) $matches[1];
            return self::tryFrom($errorCode);
        }

        return null;
    }
}
