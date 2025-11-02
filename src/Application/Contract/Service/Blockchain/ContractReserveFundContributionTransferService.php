<?php

namespace App\Application\Contract\Service\Blockchain;

use App\Application\Contract\Transformer\ContractReserveFundContributionTransformer;
use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\ContractReserveFundContributionOperation;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractNames;
use App\Domain\Contract\Exception\ContractExecutionFailedException;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Contract\ContractReserveFundContribution;
use App\Message\CheckContractBalanceMessage;
use App\Persistence\PersistorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ContractReserveFundContributionTransferService
{
    public function __construct(
        private readonly ContractReserveFundContributionOperation $contractReserveFundContributionOperation,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly ContractReserveFundContributionTransformer $contractReserveFundContributionTransformer,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly PersistorInterface $persistor,
        private readonly MessageBusInterface $bus,
    ) {
    }

    public function processReserveFundContribution(ContractReserveFundContribution $contractReserveFundContribution)
    {
        $contractTransaction = null;

        try {
            $trxResponse = $this->contractReserveFundContributionOperation->contributeToReserveFund($contractReserveFundContribution);
            $trxResult = $this->scContractResultBuilder->getResultDataFromTransactionResponse($trxResponse);

            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $contractReserveFundContribution->getContract()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::add_company_transfer->name,
                [$trxResult],
                $trxResponse->getTxHash(),
                $trxResponse->getCreatedAt()
            );

            $this->contractReserveFundContributionTransformer->updateEntityAsTransferred($contractTransaction, $contractReserveFundContribution);
            $this->persistor->persistAndFlush([$contractTransaction, $contractReserveFundContribution]);
            $this->bus->dispatch(new CheckContractBalanceMessage($contractReserveFundContribution->getContract()->getId(), $trxResponse->getLedger()));
        } catch (TransactionExceptionInterface $ex) {
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $contractReserveFundContribution->getContract()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::add_company_transfer->name,
                $ex
            );

            $this->contractReserveFundContributionTransformer->updateEntityAsFailed($contractTransaction, $contractReserveFundContribution);
            $this->persistor->persistAndFlush([$contractTransaction, $contractReserveFundContribution]);
            
            throw ContractExecutionFailedException::fromContractTransaction($contractTransaction);
        } 
    }
}
