<?php

namespace App\Presentation\Contract\DTO\Input;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

readonly class CreateContractReserveFundContributionDtoInput
{
    public function __construct(
        #[NotBlank(message: 'Amount cannot be empty')]
        #[GreaterThan(0, message: 'Amount must be greater than 0')]
        public float|int $amount
    ){}
}
