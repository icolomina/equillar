<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace App\Application\Contract\Transformer;

use App\Domain\Contract\ContractBalanceStatus;
use App\Domain\Utils\CurrencyFormatter;
use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractBalance;
use App\Entity\ContractTransaction;
use App\Presentation\Contract\DTO\Output\ContractBalanceDtoOutput;

class ContractBalanceEntityTransformer
{
    public function __construct(
        private readonly CurrencyFormatter $currencyFormatter,
    ) {
    }

    public function fromContractInvestmentToBalance(Contract $contract): ContractBalance
    {
        $contractBalance = new ContractBalance();
        $contractBalance->setContract($contract);
        $contractBalance->setCreatedAt(new \DateTimeImmutable());

        return $contractBalance;
    }

    public function fromEntityToOutputDto(?ContractBalance $contractBalance, Contract $contract, bool $showCommission): ContractBalanceDtoOutput
    {
        $this->currencyFormatter->loadFormatter($contract->getToken()->getLocale());

        $contractBalanceAvailable = $contractBalance?->getAvailable() ?? 0;
        $contractBalanceReserveFund = $contractBalance?->getReserveFund() ?? 0;
        $contractBalanceComission = $contractBalance?->getComission() ?? 0;
        $contractBalanceFundsReceived = $contractBalance?->getFundsReceived() ?? 0;
        $contractBalancePayments = $contractBalance?->getPayments() ?? 0;
        $contractBalanceWithdrawals = $contractBalance?->getProjectWithdrawals() ?? 0;
        $contractBalanceReserveFundContributons = $contractBalance?->getReserveContributions() ?? 0;
        $contractAvailableToReserveMovements = $contractBalance?->getAvailableToReserveMovements() ?? 0;

        $commision = ($showCommission) ? $contractBalanceComission : null;
        $percentageFundsReceived = round(($contractBalanceFundsReceived / $contract->getGoal()) * 100, 2);

        return new ContractBalanceDtoOutput(
            $contractBalanceAvailable,
            $contractBalanceReserveFund,
            $commision,
            $contractBalanceFundsReceived,
            $contractBalancePayments,
            $contractBalanceWithdrawals,
            $contractBalanceReserveFundContributons,
            $percentageFundsReceived,
            $contractAvailableToReserveMovements
        );
    }

    public function updateContractBalanceAsConfirmed(ContractBalance $contractBalance, ContractTransaction $contractTransaction): void
    {
        $contractBalance->setContractTransaction($contractTransaction);
        $contractBalance->setStatus(ContractBalanceStatus::CONFIRMED->name);
    }

    public function updateContractBalanceAsFailed(ContractBalance $contractBalance, ContractTransaction $contractTransaction): void
    {
        $contractBalance->setContractTransaction($contractTransaction);
        $contractBalance->setStatus(ContractBalanceStatus::FAILED->name);
    }
}
