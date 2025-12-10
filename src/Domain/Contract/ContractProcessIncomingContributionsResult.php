<?php

namespace App\Domain\Contract;

readonly class ContractProcessIncomingContributionsResult
{
    public const PROCESSED = 1;

    public const INVALID_TRANSACTION = -1;
    public const EMPTY_MUXED_DESTINATION_ACCOUNT = -2;
    public const SOURCE_ACCOUNT_AND_PROJECT_ADDRESS_NOT_MATCH = -3;
    public const CONTRIBUTION_ALREADY_PROCESSED = -4;
    public const SYSTEM_ADDRESS_DOES_NOT_HOLD_ENOUGHT_TOKEN_BALANCE = 5;

    public function __construct(
        public int $status
    ){}

    public static function fromInvalidTransaction(): self 
    {
        return new self(self::INVALID_TRANSACTION);
    }

    public static function fromEmptyDestinationMuxedAccount(): self
    {
        return new self(self::EMPTY_MUXED_DESTINATION_ACCOUNT);
    }

    public static function fromUnmatchingSourceAccountAndProjectAddress(): self
    {
        return new self(self::SOURCE_ACCOUNT_AND_PROJECT_ADDRESS_NOT_MATCH);
    }

    public static function fromContributionAlreadyProcessed(): self
    {
        return new self(self::CONTRIBUTION_ALREADY_PROCESSED);
    }   

    public static function fromSystemAddressNotHoldingEnougthBalance(): self
    {
        return new self(self::SYSTEM_ADDRESS_DOES_NOT_HOLD_ENOUGHT_TOKEN_BALANCE);
    }

    public static function fromProcessed(): self
    {
        return new self(self::PROCESSED);
    }
}
