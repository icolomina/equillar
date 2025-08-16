<?php

namespace App\Presentation\Contract\DTO\Input;

readonly class StopContractInvestmentsDtoInput
{
    public function __construct(
        public ?string $reason = null 
    ){}
}
