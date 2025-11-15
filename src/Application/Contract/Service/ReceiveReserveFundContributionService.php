<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractReserveFundContributionTransformer;
use App\Entity\Contract\ContractReserveFundContribution;
use App\Persistence\PersistorInterface;

class ReceiveReserveFundContributionService
{
    public function __construct(
        private readonly ContractReserveFundContributionTransformer $contractReserveFundContributionTransformer,
        private readonly PersistorInterface $persistor,
    ) {
    }

    public function setReserveFundContributionAsReceived(ContractReserveFundContribution $contractReserveFundContribution): void
    {
        $this->contractReserveFundContributionTransformer->updateAsReceived($contractReserveFundContribution);
        $this->persistor->persistAndFlush($contractReserveFundContribution);
    }

    public function setReserveFundContributionAsInsufficientFunds(ContractReserveFundContribution $contractReserveFundContribution): void
    {
        $this->contractReserveFundContributionTransformer->updateAsInsufficientFundsReceived($contractReserveFundContribution);
        $this->persistor->persistAndFlush($contractReserveFundContribution);
    }
}
