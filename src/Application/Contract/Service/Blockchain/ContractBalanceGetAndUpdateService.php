<?php

namespace App\Application\Contract\Service\Blockchain;

use App\Application\Contract\Mapper\GetContractBalanceMapper;
use App\Application\Contract\Transformer\ContractBalanceEntityTransformer;
use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\GetContractBalanceOperation;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractNames;
use App\Domain\Contract\Exception\ContractExecutionFailedException;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Contract\Contract;
use App\Persistence\PersistorInterface;

class ContractBalanceGetAndUpdateService
{
    public function __construct(
        private readonly GetContractBalanceOperation $getContractBalanceOperation,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly GetContractBalanceMapper $getContractBalanceMapper,
        private readonly ContractBalanceEntityTransformer $contractBalanceEntityTransformer,
        private readonly ContractEntityTransformer $contractEntityTransformer,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly PersistorInterface $persistor,
    ) {
    }

    public function getContractBalance(Contract $contract)
    {
        $contractTransaction = null;
        $contractBalance = null;

        try {
            $transactionResponse = $this->getContractBalanceOperation->getContractBalance($contract);
            $trxResult = $this->scContractResultBuilder->getResultDataFromTransactionResponse($transactionResponse);
            $contractBalance = $this->contractBalanceEntityTransformer->fromContractInvestmentToBalance($contract);

            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $contract->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::get_contract_balance->name,
                $trxResult,
                $transactionResponse->getTxHash(),
                $transactionResponse->getCreatedAt()
            );

            $this->getContractBalanceMapper->mapToEntity($trxResult, $contractBalance);
            $this->contractBalanceEntityTransformer->updateContractBalanceAsConfirmed($contractBalance, $contractTransaction);
            $this->persistor->persist([$contractTransaction, $contractBalance]);

            if ($contractBalance->getFundsReceived() >= $contract->getGoal()) {
                $this->contractEntityTransformer->updateContractAsFundsReached($contract);
                $this->persistor->persist($contract);
            }

            $this->persistor->flush();
        } catch (TransactionExceptionInterface $ex) {
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $contract->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::get_contract_balance->name,
                $ex
            );

            $this->contractBalanceEntityTransformer->updateContractBalanceAsFailed($contractBalance, $contractTransaction);
            $this->persistor->persistAndFlush([$contractTransaction, $contractBalance]);
            
            throw ContractExecutionFailedException::fromContractTransaction($contractTransaction);
        }
    }
}
