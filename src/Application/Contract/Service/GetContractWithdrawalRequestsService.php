<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractWithdrawalRequestEntityTransformer;
use App\Entity\User;
use App\Persistence\Contract\ContractWithdrawalRequestStorageInterface;
use App\Presentation\Contract\DTO\Output\ContractWithdrawalRequestDtoOutput;

class GetContractWithdrawalRequestsService
{
    public function __construct(
        private readonly ContractWithdrawalRequestStorageInterface $contractWithdrawalRequestStorage,
        private readonly ContractWithdrawalRequestEntityTransformer $contractWithdrawalRequestEntityTransformer
    ){}

    /**
     * @return ContractWithdrawalRequestDtoOutput[]
     */
    public function getContractRequestWithdrawals(User $user): array
    {
        $withdrawalRequests = $this->contractWithdrawalRequestStorage->getWithdrawalRequestsByUser($user);
        return $this->contractWithdrawalRequestEntityTransformer->fromEntitiesToOutputDtos($withdrawalRequests);
    }
}
