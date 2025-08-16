<?php

namespace App\Application\Contract\Transformer;

use App\Domain\Contract\ContractFunctions;
use App\Entity\ContractTransaction;
use Soneso\StellarSDK\Soroban\Responses\EventInfo;

class ContractTransactionEntityTransformer
{
    public function fromSuccessfulContractDeployment(string $contracAddress, string $contractName): ContractTransaction
    {
        $contractTransaction = new ContractTransaction();
        $contractTransaction->setContractAddress($contracAddress);
        $contractTransaction->setContractLabel($contractName);
        $contractTransaction->setFunctionCalled(ContractFunctions::activation->name);
        $contractTransaction->setTrxDate(new \DateTimeImmutable());

        return $contractTransaction;
    }

    public function fromFailedContractDeployment(string $contractName, string $error): ContractTransaction
    {
        $contractTransaction = new ContractTransaction();
        $contractTransaction->setContractLabel($contractName);
        $contractTransaction->setFunctionCalled(ContractFunctions::activation->name);
        $contractTransaction->setTrxDate(new \DateTimeImmutable());
        $contractTransaction->setError($error);

        return $contractTransaction;
    }

    public function fromSuccessfulTransaction(string $contracAddress, string $contractName, string $functionCalled, array $trxResult, ?string $trxHash, int $trxLedger): ContractTransaction
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

    public function fromEventInfo(string $contracAddress, string $contractName, array $trxResult, EventInfo $eventInfo): ContractTransaction
    {
        $contractTransaction = new ContractTransaction();
        $contractTransaction->setContractAddress($contracAddress);
        $contractTransaction->setContractLabel($contractName);
        $contractTransaction->setFunctionCalled('GetEvents - ContractBalanceUpdated');
        $contractTransaction->setTrxResultData($trxResult);
        $contractTransaction->setTrxHash($eventInfo->txHash);
        $contractTransaction->setTrxDate(new \DateTimeImmutable(date('Y-m-d H:i:s', $eventInfo->ledger)));

        return $contractTransaction;
    }

    public function fromFailedTransaction(?string $contractAddress, string $contractName, string $functionCalled, string $txError, ?string $txHash, int $trxLedger): ContractTransaction
    {
        $contractTransaction = new ContractTransaction();
        $contractTransaction->setContractAddress($contractAddress);
        $contractTransaction->setContractLabel($contractName);
        $contractTransaction->setFunctionCalled($functionCalled);
        $contractTransaction->setError($txError);
        $contractTransaction->setTrxHash($txHash);
        $contractTransaction->setTrxDate(new \DateTimeImmutable(date('Y-m-d H:i:s', $trxLedger)));

        return $contractTransaction;
    }

    public function fromBadEventRequest(string $contractAddress, string $contractName,  string $errorMessage): ContractTransaction
    {
        $contractTransaction = new ContractTransaction();
        $contractTransaction->setContractAddress($contractAddress);
        $contractTransaction->setContractLabel($contractName);
        $contractTransaction->setFunctionCalled('GetEvents - ContractBalanceUpdated');
        $contractTransaction->setError($errorMessage);
        $contractTransaction->setTrxDate(new \DateTimeImmutable());

        return $contractTransaction;
    }
}
