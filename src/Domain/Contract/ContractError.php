<?php

namespace App\Domain\Contract;

enum ContractError: int
{
    case AddressInsufficientBalance = 1;
    case ContractInsufficientBalance = 2;
    case AmountLessOrEqualThan0 = 4;
    case AddressHasNotInvested = 14;
    case AddressInvestmentIsNotClaimableYet = 15;
    case AddressInvestmentIsFinished = 16;
    case AddressInvestmentNextTransferNotClaimableYet = 17;
    case WithdrawalUnexpectedSignature = 21;
    case WithdrawalExpiredSignature = 22;
    case WithdrawalInvalidAmount = 23;
    case ProjectBalanceInsufficientAmount = 24;
    case ContractMustBePausedToRestartAgain = 25;
    case ContractMustBeActiveToBePaused = 26;
    case ContractMustBeActiveToInvest = 27;
    case RecipientCannotReceivePayments = 28;
    case InvalidPaymentData = 29;

    public function getMessage(): string
    {
        return match ($this) {
            self::AddressInsufficientBalance => 'Address has insufficient balance to perform this operation',
            self::ContractInsufficientBalance => 'Contract has insufficient balance to complete this transaction',
            self::AmountLessOrEqualThan0 => 'Amount must be greater than zero',
            self::AddressHasNotInvested => 'This address has not made any investment in the project',
            self::AddressInvestmentIsNotClaimableYet => 'Investment is not yet available to be claimed',
            self::AddressInvestmentIsFinished => 'Investment has finished and no more operations can be performed',
            self::AddressInvestmentNextTransferNotClaimableYet => 'Next investment transfer is not yet available to be claimed',
            self::WithdrawalUnexpectedSignature => 'Withdrawal signature is invalid or does not match the expected one',
            self::WithdrawalExpiredSignature => 'Withdrawal signature has expired and is no longer valid',
            self::WithdrawalInvalidAmount => 'Withdrawal amount is invalid',
            self::ProjectBalanceInsufficientAmount => 'Project does not have sufficient balance to perform this operation',
            self::ContractMustBePausedToRestartAgain => 'Contract must be paused in order to be restarted',
            self::ContractMustBeActiveToBePaused => 'Contract must be active in order to be paused',
            self::ContractMustBeActiveToInvest => 'Contract must be active to make investments',
            self::RecipientCannotReceivePayments => 'The recipient address cannot receive payments in the established contract asset. Maybe the asset trustline has not been created yet.',
            self::InvalidPaymentData => 'The payment data provided is invalid',
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
