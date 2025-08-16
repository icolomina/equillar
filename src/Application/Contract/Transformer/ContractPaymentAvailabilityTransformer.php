<?php

namespace App\Application\Contract\Transformer;

use App\Domain\Contract\ContractPaymentAvailabilityStatus;
use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractPaymentAvailability;
use App\Entity\ContractTransaction;

class ContractPaymentAvailabilityTransformer
{
    public function fromContractToPaymentAvailability(Contract $contract): ContractPaymentAvailability
    {
        $contractPaymentAvailability = new ContractPaymentAvailability();
        $contractPaymentAvailability->setContract($contract);
        $contractPaymentAvailability->setCreatedAt(new \DateTimeImmutable());
        $contractPaymentAvailability->setStatus(ContractPaymentAvailabilityStatus::PENDING->name);

        return $contractPaymentAvailability;
    }

    public function updateContractPaymentAvalabilityAsProcessed(ContractPaymentAvailability $contractPaymentAvailability, ContractTransaction $contractTransaction, float $requiredFunds): void
    {
        $contractPaymentAvailability->setCheckedAt(new \DateTimeImmutable());
        $contractPaymentAvailability->setRequiredFunds($requiredFunds);
        $contractPaymentAvailability->setContractTransaction($contractTransaction);
        $contractPaymentAvailability->setStatus(ContractPaymentAvailabilityStatus::PROCESSED->name);
    }

    public function updateContractPaymentAvalabilityAsFailed(ContractPaymentAvailability $contractPaymentAvailability, ContractTransaction $contractTransaction): void
    {
        $contractPaymentAvailability->setContractTransaction($contractTransaction);
        $contractPaymentAvailability->setStatus(ContractPaymentAvailabilityStatus::FAILED->name);
    }
}
