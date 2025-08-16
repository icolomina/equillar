<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractReserveFundContributionTransformer;
use App\Entity\Contract\ContractReserveFundContribution;
use App\Persistence\PersistorInterface;

class ReceiveReserveFundContributionService
{
    public function __construct(
        private readonly ContractReserveFundContributionTransformer $contractReserveFundContributionTransformer,
        private readonly PersistorInterface $persistor
    ){}

    public function setReserveFundContributionAsReceived(ContractReserveFundContribution $contractReserveFundContribution): void
    {
        $this->contractReserveFundContributionTransformer->updateAsReceived($contractReserveFundContribution);
        $this->persistor->persist($contractReserveFundContribution);
    }
}
