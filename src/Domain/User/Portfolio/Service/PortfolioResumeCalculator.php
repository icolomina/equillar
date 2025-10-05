<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\User\Portfolio\Service;

use App\Domain\Contract\ContractReturnType;
use App\Domain\User\Portfolio\PortfolioResume;
use App\Domain\User\Portfolio\PortfolioResumeParameter;
use App\Domain\UserContract\UserContractStatus;
use App\Entity\Contract\UserContract;
use App\Entity\Token;

class PortfolioResumeCalculator
{
    private array $tokensUsed;

    /**
     * @param UserContract[] $userContracts
     */
    public function getResume(array $userContracts): PortfolioResume
    {
        $depositedResume = new PortfolioResumeParameter('deposited');
        $interestsResume = new PortfolioResumeParameter('interests');
        $totalResume = new PortfolioResumeParameter('total');
        $totalChargedResume = new PortfolioResumeParameter('total_charged');
        $totalPendingToChargeResume = new PortfolioResumeParameter('total_pending_to_charge');
        $totalClaimableResume = new PortfolioResumeParameter('total_claimable');

        $this->tokensUsed = [];

        foreach ($userContracts as $uc) {
            $token = $uc->getContract()->getToken();
            $this->addTokenUsed($token);

            $depositedResume->increment($token->getCode(), $uc->getBalance());
            $interestsResume->increment($token->getCode(), $uc->getInterests() ?? 0);
            $totalResume->increment($token->getCode(), $uc->getTotal() ?? 0);

            (in_array($uc->getContract()->getReturnType(), [ContractReturnType::REVERSE_LOAN->value, ContractReturnType::COUPON->value]))
                ? $totalPendingToChargeResume->increment($token->getCode(), $uc->getTotal() ?? 0)
                : $totalPendingToChargeResume->increment($token->getCode(), 0)
            ;

            ($uc->getStatus() === UserContractStatus::CASH_FLOWING->name)
                ? $totalChargedResume->increment($token->getCode(), $uc->getTotalCharged())
                : $totalChargedResume->increment($token->getCode(), 0)
            ;

            ($uc->getStatus() === UserContractStatus::CLAIMABLE->name)
                ? $totalClaimableResume->increment($token->getCode(), $uc->getTotal())
                : $totalClaimableResume->increment($token->getCode(), 0)
            ;
        }

        $depositedResumeFormattedData = $this->normalizeAmountsByToken($depositedResume);
        $interestsResumeFormattedData = $this->normalizeAmountsByToken($interestsResume);
        $totalResumeFormattedData = $this->normalizeAmountsByToken($totalResume);
        $totalChargedResumeFormattedData = $this->normalizeAmountsByToken($totalChargedResume);
        $totalPendingToChargeResumeFormattedData = $this->normalizeAmountsByToken($totalPendingToChargeResume);
        $totalClaimableResumeFormattedData = $this->normalizeAmountsByToken($totalClaimableResume);

        return new PortfolioResume(
            $depositedResumeFormattedData,
            $interestsResumeFormattedData,
            $totalResumeFormattedData,
            $totalChargedResumeFormattedData,
            $totalPendingToChargeResumeFormattedData,
            $totalClaimableResumeFormattedData
        );
    }

    /**
     * @return array<string, string>
     */
    private function normalizeAmountsByToken(\IteratorAggregate $iterator): array
    {
        $formattedData = [];
        foreach ($iterator as $token => $value) {
            $numberFormatter = new \NumberFormatter($this->tokensUsed[$token]->getLocale(), \NumberFormatter::CURRENCY);
            $formattedData[$token] = $numberFormatter->formatCurrency($value, $this->tokensUsed[$token]->getReferencedCurrency());
        }

        return $formattedData;
    }

    private function addTokenUsed(Token $token): void
    {
        if (!isset($this->tokensUsed[$token->getCode()])) {
            $this->tokensUsed[$token->getCode()] = $token;
        }
    }
}
