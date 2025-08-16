<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\StopInvestmentsOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Contract\Contract;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class StopInvestmentsOperation
{
    public function __construct(
        private readonly StopInvestmentsOperationBuilder $stopDepositsOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService,
    ){}

    public function stopInventments(Contract $contract): GetTransactionResponse
    {
        $invokeContractHostFunction = $this->stopDepositsOperationBuilder->build($contract);
        return $this->processTransactionService->sendTransaction($invokeContractHostFunction, true);
    }
}
