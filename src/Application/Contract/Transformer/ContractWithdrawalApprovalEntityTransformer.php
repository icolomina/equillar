<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\Contract\Transformer;

use App\Domain\Contract\ContractWithdrawalStatus;
use App\Entity\Contract\ContractWithdrawalApproval;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Entity\ContractTransaction;
use App\Presentation\Contract\DTO\Output\ContractWithdrawalApprovalDtoOutput;

class ContractWithdrawalApprovalEntityTransformer
{
    public function __construct(
        private readonly ContractWithdrawalRequestEntityTransformer $contractWithdrawalRequestEntityTransformer,
    ) {
    }

    public function fromRequestApprovedToEntity(ContractWithdrawalRequest $contractInvestmentWithdrawalRequest, ContractTransaction $contractTransaction): ContractWithdrawalApproval
    {
        $approvement = new ContractWithdrawalApproval();
        $approvement->setContractWithdrawalRequest($contractInvestmentWithdrawalRequest);
        $approvement->setApprovedAt(new \DateTimeImmutable());
        $approvement->setStatus(ContractWithdrawalStatus::FUNDS_SENT->name);
        $approvement->setContractTransaction($contractTransaction);

        return $approvement;
    }

    public function fromRequestApprovalFailureToEntity(ContractWithdrawalRequest $contractInvestmentWithdrawalRequest, ContractTransaction $contractTransaction): ContractWithdrawalApproval
    {
        $approvement = new ContractWithdrawalApproval();
        $approvement->setContractWithdrawalRequest($contractInvestmentWithdrawalRequest);
        $approvement->setStatus(ContractWithdrawalStatus::FAILED->name);
        $approvement->setContractTransaction($contractTransaction);

        return $approvement;
    }

    public function fromEntityToOutputDto(ContractWithdrawalApproval $contractWithdrawalApproval): ContractWithdrawalApprovalDtoOutput
    {
        $contractWithdrawalRequestOutputDto = $this->contractWithdrawalRequestEntityTransformer->fromEntityToOutputDto($contractWithdrawalApproval->getContractWithdrawalRequest());

        return new ContractWithdrawalApprovalDtoOutput(
            $contractWithdrawalRequestOutputDto,
            $contractWithdrawalApproval->getApprovedAt()?->format('Y-m-d H:i'),
            $contractWithdrawalApproval->getStatus()
        );
    }
}
