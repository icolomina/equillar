<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractWithdrawalRequestEntityTransformer;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Persistence\PersistorInterface;

class ContractWithdrawalConfirmationService
{
    public function __construct(
        private readonly ContractWithdrawalRequestEntityTransformer $contractWithdrawalRequestEntityTransformer,
        private readonly PersistorInterface $persistor,
    ) {
    }

    public function confirmWithdrawal(ContractWithdrawalRequest $contractWithdrawalRequest): void
    {
        $this->contractWithdrawalRequestEntityTransformer->updateWithdrawalRequestAsConfirmed($contractWithdrawalRequest);
        $this->persistor->persistAndFlush($contractWithdrawalRequest);
    }
}
