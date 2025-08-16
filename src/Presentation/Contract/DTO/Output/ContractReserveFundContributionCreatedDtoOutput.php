<?php

namespace App\Presentation\Contract\DTO\Output;

readonly class ContractReserveFundContributionCreatedDtoOutput
{
    public function __construct(
        public string $contributionId,
        public string $destinationAddress,
        public float $amount
    ){}
}
