<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractBalanceEntityTransformer;
use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Application\Contract\Mapper\GetContractBalanceMapper;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\Investment\GetContractBalanceOperation;
use App\Domain\Contract\ContractFunctions;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Investment\ContractInvestment;
use App\Persistence\PersistorInterface;
use App\Domain\Contract\ContractNames;
use App\Persistence\Investment\Contract\ContractInvestmentBalanceStorageInterface;

class GetContractBalanceService
{
    public function __construct(
        private readonly GetContractBalanceOperation $getContractBalanceOperation,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly GetContractBalanceMapper $getContractBalanceMapper,
        private readonly ContractBalanceEntityTransformer $contractBalanceEntityTransformer,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly ContractInvestmentBalanceStorageInterface $contractInvestmentBalanceStorage,
        private readonly PersistorInterface $persistor
    ){}

    public function getContractBalance(ContractInvestment $contractInvestment)
    {

        $contractTransaction = null;
        $contractInvestmentBalance = null;

        try{
            $transactionResponse = $this->getContractBalanceOperation->getContractBalance($contractInvestment);
            $trxResult = $this->scContractResultBuilder->getResultData($transactionResponse);
            $contractInvestmentBalance = $this->contractInvestmentBalanceStorage->getLastBalanceByContractInvestment($contractInvestment);
            if(!$contractInvestmentBalance){
                $contractInvestmentBalance = $this->contractBalanceEntityTransformer->fromContractInvestmentToBalance($contractInvestment);
            }

            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $contractInvestment->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::get_contract_balance->name,
                $trxResult,
                $transactionResponse->getTxHash(),
                $transactionResponse->getLatestLedger() ?? $transactionResponse->getLedger()
            );

            $this->getContractBalanceMapper->mapToEntity($trxResult, $contractInvestmentBalance);
            $contractInvestmentBalance->setContractTransaction($contractTransaction);
            $contractInvestmentBalance->setStatus($transactionResponse->getStatus());
        }
        catch(TransactionExceptionInterface $ex){
            $contractInvestmentBalance->setStatus($ex->getStatus());
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $contractInvestment->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::get_contract_balance->name,
                $ex->getError(),
                $ex->getHash(),
                $ex->getFailureLedger()
            );

            $contractInvestmentBalance->setContractTransaction($contractTransaction);
        }
        finally {
            $this->persistor->persistAndFlush([$contractTransaction, $contractInvestmentBalance]);
        }
    }
}
