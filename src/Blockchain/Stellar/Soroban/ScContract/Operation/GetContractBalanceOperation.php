<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\GetContractBalanceOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Investment\ContractInvestment;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class GetContractBalanceOperation
{
    public function __construct(
        private readonly GetContractBalanceOperationBuilder $getContractBalanceOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService
    ){}

    public function getContractBalance(ContractInvestment $contract): GetTransactionResponse
    {
        $invokeContractHostFunction = $this->getContractBalanceOperationBuilder->build($contract);
        return $this->processTransactionService->sendTransaction($invokeContractHostFunction, true);
    }
}
