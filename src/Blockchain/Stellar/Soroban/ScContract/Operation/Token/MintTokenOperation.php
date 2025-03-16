<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Token;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Token\Builder\MintTokenOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Blockchain\Stellar\Soroban\Transaction\RegisterTransactionService;
use App\Entity\Token;

class MintTokenOperation
{
    public function __construct(
        private readonly MintTokenOperationBuilder $mintTokenOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService
    ){}

    public function mintToken(Token $token, string $addressToMint, string $amount)
    {
        $invokeContractHostFunction = $this->mintTokenOperationBuilder->build($token, $addressToMint, $amount);
        $transactionResponse = $this->processTransactionService->sendTransaction($invokeContractHostFunction, true);
        $resultValue = $transactionResponse->getResultValue();

       // $this->registerTransactionService->registerContractTransaction($token->getAddress(), 'Token', 'mint', $transactionResponse);

        if($resultValue->getError()) {
            throw new \RuntimeException('Address cannot be minted with token: ' . $resultValue->getError()->getCode()->getValue());
        }
    }
}
