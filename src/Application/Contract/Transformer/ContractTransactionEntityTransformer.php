<?php

namespace App\Application\Contract\Transformer;

use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\ContractTransaction;

class ContractTransactionEntityTransformer
{

    public function __construct(
        private readonly ScContractResultBuilder $scContractResultBuilder
    ){}

    public function fromSuccessfulTransaction(string $contracAddress, string $contractName, string $functionCalled, array $trxResult, string $trxHash, int $trxLedger): ContractTransaction
    {
        $contractTransaction = new ContractTransaction();
        $contractTransaction->setContractAddress($contracAddress);
        $contractTransaction->setContractLabel($contractName);
        $contractTransaction->setFunctionCalled($functionCalled);
        $contractTransaction->setTrxResultData($trxResult);
        $contractTransaction->setTrxHash($trxHash);
        $contractTransaction->setTrxDate(new \DateTimeImmutable(date('Y-m-d H:i:s', $trxLedger)));

        return $contractTransaction;
    }

    public function fromFailedTransaction(string $contracAddress, string $contractName, string $functionCalled, string $txError, ?string $txHash, int $trxLedger): ContractTransaction
    {
        $contractTransaction = new ContractTransaction();
        $contractTransaction->setContractAddress($contracAddress);
        $contractTransaction->setContractLabel($contractName);
        $contractTransaction->setFunctionCalled($functionCalled);
        $contractTransaction->setError($txError);
        $contractTransaction->setTrxHash($txHash);
        $contractTransaction->setTrxDate(new \DateTimeImmutable(date('Y-m-d H:i:s', $trxLedger)));

        return $contractTransaction;
    }
}
