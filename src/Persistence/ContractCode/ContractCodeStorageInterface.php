<?php

namespace App\Persistence\ContractCode;

use App\Entity\ContractCode;

interface ContractCodeStorageInterface
{
    public function getLastdeployedContractCode(): ?ContractCode;
}
