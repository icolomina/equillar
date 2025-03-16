<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Token;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Token\Builder\GetBalanceOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Token;

class GetBalanceOperation
{
    public function __construct(
        private readonly GetBalanceOperationBuilder $getBalanceOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService,
        private readonly ScContractResultBuilder $scContractResultBuilder
    ){}

    public function ra(Token $token, string $addressToCheck): mixed
    {
        $invokeContractHostFunction = $this->getBalanceOperationBuilder->build($token, $addressToCheck);
        $transactionResponse = $this->processTransactionService->sendTransaction($invokeContractHostFunction);
        $resultValue = $transactionResponse->getResultValue();

       // $this->registerTransactionService->registerContractTransaction($token->getAddress(), 'Token', 'mint', $transactionResponse);

        if($resultValue->getError()) {
            throw new \RuntimeException('Unable to check balance for address: ' . $resultValue->getError()->getCode()->getValue());
        }

        return $this->scContractResultBuilder->getResultData($transactionResponse);
    }
}
