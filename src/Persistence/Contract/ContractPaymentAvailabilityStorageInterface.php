<?php

namespace App\Persistence\Contract;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractPaymentAvailability;

interface ContractPaymentAvailabilityStorageInterface
{
    public function getById(int $id): ?ContractPaymentAvailability;
    public function getLastProcessedForContract(Contract $contract): ?ContractPaymentAvailability;
}