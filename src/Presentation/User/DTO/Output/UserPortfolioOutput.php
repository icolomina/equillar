<?php

namespace App\Presentation\User\DTO\Output;

use App\Domain\User\Portfolio\PortfolioResume;
use App\Presentation\UserContract\DTO\Output\UserContractDtoOutput;

readonly class UserPortfolioOutput
{
    /**
     * @param UserContractDtoOutput[] $userContracts
     */
    public function __construct(
        public PortfolioResume $resume,
        public array $userContracts,
        public bool $isEmpty
    ){}
}
