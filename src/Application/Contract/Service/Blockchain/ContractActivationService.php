<?php

namespace App\Application\Contract\Service\Blockchain;

use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\ContractActivationOperation;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractNames;
use App\Domain\Contract\Exception\ContractExecutionFailedException;
use App\Entity\Contract\Contract;
use App\Persistence\PersistorInterface;

class ContractActivationService
{
    public function __construct(
        private readonly ContractActivationOperation $contractActivationOperation,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly ContractEntityTransformer $contractEntityTransformer,
        private readonly PersistorInterface $persistor
    ) {
    }

    public function activateContract(Contract $contract): void
    {
        $contractTransaction = null;

        try {
            $transactionResponse = $this->contractActivationOperation->activateContract($contract);
            $contractAddress = $transactionResponse->getCreatedContractId();
            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $contractAddress,
                ContractNames::INVESTMENT->name,
                ContractFunctions::activation->name,
                [$contractAddress],
                $transactionResponse->getTxHash(),
                $transactionResponse->getCreatedAt()
            );

            $this->contractEntityTransformer->updateContractAsActive($contract, $contractAddress, $contractTransaction);
            $this->persistor->persistAndFlush([$contractTransaction, $contract]);

        } 
        catch (TransactionExceptionInterface $ex) {
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                null,
                ContractNames::INVESTMENT->value,
                ContractFunctions::activation->name,
                $ex
            );

            $this->contractEntityTransformer->updateContractAsDeploymentFailed($contract, $contractTransaction);
            $this->persistor->persistAndFlush([$contractTransaction, $contract]);
            
            throw ContractExecutionFailedException::fromContractTransaction($contractTransaction);
        }
    }
}
