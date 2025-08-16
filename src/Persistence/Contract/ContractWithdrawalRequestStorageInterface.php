<?php

namespace App\Persistence\Contract;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Entity\User;

interface ContractWithdrawalRequestStorageInterface
{
    public function getWithdrawalRequestById(int $id): ?ContractWithdrawalRequest;
    public function getWithdrawalRequestByUuid(string $uuid): ?ContractWithdrawalRequest;
    public function getWithdrawalRequestsByContract(Contract $contract): array;
    public function getWithdrawalRequestsByUser(User $user): array;
    public function getTotalsAmountByApprovedWithdrawalsAndContract(Contract $contract): int|float|null;
}
