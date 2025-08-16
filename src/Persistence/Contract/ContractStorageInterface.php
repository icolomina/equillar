<?php

namespace App\Persistence\Contract;

use App\Entity\Contract\Contract;
use App\Entity\User;

interface ContractStorageInterface
{
    public function getContractsByIssuer(User $issuer): array;
    public function getAllContracts(): array;
    public function getInitializedContracts(): array;
    public function getContractByAddress(string $address): ?Contract;
    public function getContractById(string|int $id): ?Contract;
    public function markContractAsInitalized(Contract $contract, string $contractAddress, string $projectAddress, int $returnType, int $returnMonths, int $minPerInvestment): void;
    public function markContractAsFundsReached(Contract $contract): void;
    public function markContractAsApproved(Contract $contract): void;
}
