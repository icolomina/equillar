<?php

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
