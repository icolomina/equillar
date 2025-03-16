<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\ProjectWithdrawalOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Investment\ContractInvestment;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class ProjectWithdrawalOperation
{
    public function __construct(
        private readonly ProjectWithdrawalOperationBuilder $projectWithdrawalOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService
    ){}

    public function projectWithdrawn(ContractInvestment $contractInvestment, float $amount): GetTransactionResponse
    {
        $operation = $this->projectWithdrawalOperationBuilder->build($contractInvestment, $amount);
        return $this->processTransactionService->sendTransaction($operation, true);
    }
}
