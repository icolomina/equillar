<?php

namespace App\Application\UserContract\Service;

use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Application\UserContract\Mapper\UserInvestmentTrxResultMapper;
use App\Application\UserContract\Transformer\UserContractEntityTransformer;
use App\Application\UserContract\Transformer\UserContractPaymentEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\PayUserContractOperation;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractNames;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Contract\UserContractPayment;
use App\Message\CheckContractBalanceMessage;
use App\Persistence\PersistorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PayUserContractService
{
    public function __construct(
        private readonly PayUserContractOperation $payUserContractOperation,
        private readonly PersistorInterface $persistor,
        private readonly UserInvestmentTrxResultMapper $userInvestmentTrxResultMapper,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly UserContractEntityTransformer $userContractEntityTransformer,
        private readonly UserContractPaymentEntityTransformer $userContractPaymentEntityTransformer,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly MessageBusInterface $bus

    ){}

    public function payUserContract(UserContractPayment $userContractPayment, bool $useCurrentHash = false): void
    {

        $contractTransaction = null;

        try{
            $userContract        = $userContractPayment->getUserContract();
            $transactionResponse = ($useCurrentHash && $userContractPayment->getHash())
                ? $this->payUserContractOperation->processPayUserContractTransaction($userContractPayment->getHash())
                : $this->payUserContractOperation->payUserContract($userContract)
            ;
            
            $trxResult = $this->scContractResultBuilder->getResultDataFromTransactionResponse($transactionResponse);
            $trxHash   = $transactionResponse->getTxHash();
            $this->userInvestmentTrxResultMapper->mapToEntity($trxResult, $userContract);
            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $userContract->getContract()->getAddress(),
                ContractNames::INVESTMENT->name,
                ContractFunctions::process_investor_payment->name,
                $trxResult,
                $trxHash,
                $transactionResponse->getLedger()
            );

            $paidAt = new \DateTimeImmutable(date('Y-m-d H:i:s', (int)$transactionResponse->getCreatedAt()));
            $this->userContractEntityTransformer->updateUserContractWithNewClaim($userContract, $paidAt);
            $this->userContractPaymentEntityTransformer->updatePaymentWithSuccessfulTransactionResult(
                $userContractPayment,
                $trxHash,
                $userContract->getRegularPayment(),
                $paidAt
            );

            $userContractPayment->setTransaction($contractTransaction);
            $this->persistor->persistAndFlush([$contractTransaction, $userContract, $userContractPayment]);
            $this->bus->dispatch(new CheckContractBalanceMessage($userContract->getContract()->getId(), $transactionResponse->getLedger()));
        }
        catch(TransactionExceptionInterface $ex) {
            $userContractPayment->setStatus($ex->getStatus());
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $userContractPayment->getUserContract()->getContract()->getAddress(),
                ContractNames::INVESTMENT->name,
                ContractFunctions::process_investor_payment->name,
                $ex->getError(),
                $ex->getHash(),
                $ex->getFailureLedger()
            );


            $userContractPayment->setTransaction($contractTransaction);
            $this->persistor->persistAndFlush([$contractTransaction, $userContractPayment]);
        }
        
    }
}
