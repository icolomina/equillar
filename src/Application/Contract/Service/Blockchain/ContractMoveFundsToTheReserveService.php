<?php

namespace App\Application\Contract\Service\Blockchain;

use App\Application\Contract\Transformer\ContractBalanceMovementTransformer;
use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\ContractAvailableToReserveFundOperation;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractNames;
use App\Domain\Contract\Exception\ContractExecutionFailedException;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Contract\ContractBalanceMovement;
use App\Message\CheckContractBalanceMessage;
use App\Persistence\PersistorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ContractMoveFundsToTheReserveService
{
    public function __construct(
        private readonly ContractAvailableToReserveFundOperation $contractAvailableToReserveFundOperation,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly ContractBalanceMovementTransformer $contractBalanceMovementTransformer,
        private readonly MessageBusInterface $bus,
        private readonly PersistorInterface $persistor
    ){}

    public function moveAvailableFundsToTheReserve(ContractBalanceMovement $contractBalanceMovement): void
    {
        $contractTransaction = null;

        try {
            $trxResponse = $this->contractAvailableToReserveFundOperation->moveAvailableFundsToReserve($contractBalanceMovement);
            $trxResult = $this->scContractResultBuilder->getResultDataFromTransactionResponse($trxResponse);

            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $contractBalanceMovement->getContract()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::move_funds_to_the_reserve->name,
                [$trxResult],
                $trxResponse->getTxHash(),
                $trxResponse->getCreatedAt()
            );

            $this->contractBalanceMovementTransformer->updateContractBalanceMovementAsMoved($contractBalanceMovement, $contractTransaction);
            $this->persistor->persistAndFlush([$contractTransaction, $contractBalanceMovement]);
            $this->bus->dispatch(new CheckContractBalanceMessage($contractBalanceMovement->getContract()->getId(), $trxResponse->getLedger()));
        } catch (TransactionExceptionInterface $ex) {
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $contractBalanceMovement->getContract()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::move_funds_to_the_reserve->name,
                $ex
            );

            $this->contractBalanceMovementTransformer->updateContractBalanceMovementAsFailed($contractBalanceMovement, $contractTransaction);
            $this->persistor->persistAndFlush([$contractTransaction, $contractBalanceMovement]);
            
            throw ContractExecutionFailedException::fromContractTransaction($contractTransaction);
        } 
    }
}
