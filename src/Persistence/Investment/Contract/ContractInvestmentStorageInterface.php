<?php

namespace App\Persistence\Investment\Contract;

use App\Entity\Investment\ContractInvestment;
use App\Entity\User;

interface ContractInvestmentStorageInterface
{
    public function saveContract(ContractInvestment $contract): void;
    public function getContractsByIssuer(User $issuer): array;
    public function getAllContracts(): array;
    public function getInitializedContracts(): array;
    public function getContractByAddress(string $address): ?ContractInvestment;
    public function getContractById(int $id): ?ContractInvestment;
    public function markContractAsInitalized(ContractInvestment $contract, string $contractAddress, string $projectAddress, int $returnType, int $returnMonths, int $minPerInvestment): void;
    public function markContractAsFundsReached(ContractInvestment $contract): void;
    public function markContractAsApproved(ContractInvestment $contract): void;
}
