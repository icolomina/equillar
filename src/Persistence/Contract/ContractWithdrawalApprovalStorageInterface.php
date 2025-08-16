<?php

namespace App\Persistence\Contract;

use App\Entity\Contract\ContractWithdrawalApproval;
use App\Entity\Contract\ContractWithdrawalRequest;

interface ContractWithdrawalApprovalStorageInterface
{
    public function getByWithdrawalRequest(ContractWithdrawalRequest $withdrawalRequest): ?ContractWithdrawalApproval ;
}
