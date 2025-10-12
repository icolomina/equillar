<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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

    public function fromSuccessfulTransaction(string $contracAddress, string $contractName, string $functionCalled, array $trxResult, ?string $trxHash, ?string $trxCreatedAt): ContractTransaction
    {

        $trxDate = new \DateTimeImmutable($trxCreatedAt ?? date('Y-m-d H:i:s'));

        $contractTransaction = new ContractTransaction();
        $contractTransaction->setContractAddress($contracAddress);
        $contractTransaction->setContractLabel($contractName);
        $contractTransaction->setFunctionCalled($functionCalled);
        $contractTransaction->setTrxResultData($trxResult);
        $contractTransaction->setTrxHash($trxHash);
        $contractTransaction->setTrxDate($trxDate);

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
        $contractTransaction->setTrxDate(new \DateTimeImmutable($eventInfo->ledgerClosedAt));

        return $contractTransaction;
    }

    public function fromFailedTransaction(?string $contractAddress, string $contractName, string $functionCalled, string $txError, ?string $txHash, ?string $trxCreatedAt): ContractTransaction
    {
        $trxDate = new \DateTimeImmutable($trxCreatedAt ?? date('Y-m-d H:i:s'));

        $contractTransaction = new ContractTransaction();
        $contractTransaction->setContractAddress($contractAddress);
        $contractTransaction->setContractLabel($contractName);
        $contractTransaction->setFunctionCalled($functionCalled);
        $contractTransaction->setError($txError);
        $contractTransaction->setTrxHash($txHash);
        $contractTransaction->setTrxDate($trxDate);

        return $contractTransaction;
    }

    public function fromBadEventRequest(string $contractAddress, string $contractName, string $errorMessage): ContractTransaction
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
