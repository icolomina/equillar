<?php

namespace App\Application\Contract\Service\Blockchain;

use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Application\Contract\Transformer\ContractWithdrawalApprovalEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\ContractWithdrawalOperation;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractNames;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Message\CheckContractBalanceMessage;
use App\Persistence\PersistorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ContractWithdrawalApprovalService
{
    public function __construct(
        private readonly ContractWithdrawalOperation $contractWithdrawalOperation,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly ContractWithdrawalApprovalEntityTransformer $contractWithdrawalApprovalEntityTransformer,
        private readonly PersistorInterface $persistor,
        private readonly MessageBusInterface $bus,
    ) {
    }

    public function processProjectWithdrawal(ContractWithdrawalRequest $contractWithdrawalRequest): void
    {
        $contractTransaction = null;
        $contractWithdrawalApproval = null;

        try {
            $trxResponse = $this->contractWithdrawalOperation->projectWithdrawn($contractWithdrawalRequest->getContract(), $contractWithdrawalRequest->getRequestedAmount());
            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $contractWithdrawalRequest->getContract()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::single_withdrawn->name,
                [true],
                $trxResponse->getTxHash(),
                $trxResponse->getCreatedAt()
            );

            $contractWithdrawalApproval = $this->contractWithdrawalApprovalEntityTransformer->fromRequestApprovedToEntity($contractWithdrawalRequest, $contractTransaction);
            $this->bus->dispatch(new CheckContractBalanceMessage($contractWithdrawalRequest->getContract()->getId(), $trxResponse->getLedger()));
        } catch (TransactionExceptionInterface $ex) {
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $contractWithdrawalRequest->getContract()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::single_withdrawn->name,
                $ex->getError(),
                $ex->getHash(),
                $ex->getCreatedAt()
            );

            $contractWithdrawalApproval = $this->contractWithdrawalApprovalEntityTransformer->fromRequestApprovalFailureToEntity($contractWithdrawalRequest, $contractTransaction);
        } finally {
            $this->persistor->persistAndFlush([$contractTransaction, $contractWithdrawalRequest, $contractWithdrawalApproval]);
        }
    }
}
