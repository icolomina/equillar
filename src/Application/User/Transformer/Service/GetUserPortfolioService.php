<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace App\Application\User\Transformer\Service;

use App\Application\UserContract\Transformer\UserContractEntityTransformer;
use App\Domain\User\Portfolio\Service\PortfolioResumeCalculator;
use App\Entity\User;
use App\Persistence\UserContract\UserContractStorageInterface;
use App\Presentation\User\DTO\Output\UserPortfolioOutput;

class GetUserPortfolioService
{
    public function __construct(
        private readonly UserContractStorageInterface $userContractInvestmentStorage,
        private readonly PortfolioResumeCalculator $portfolioResumeCalculator,
        private readonly UserContractEntityTransformer $userContractEntityTransformer,
    ) {
    }

    public function getPortfolio(User $user): UserPortfolioOutput
    {
        $userContracts = $this->userContractInvestmentStorage->getPorfolioUserContracts($user);
        $resume = $this->portfolioResumeCalculator->getResume($userContracts);

        return new UserPortfolioOutput(
            $resume,
            $this->userContractEntityTransformer->fromEntitiesToOutputDtos($userContracts),
            empty($userContracts)
        );
    }
}
