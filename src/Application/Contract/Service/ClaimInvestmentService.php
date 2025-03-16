<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\Investment\ClaimOperation;
use App\Application\UserContract\Mapper\UserInvestmentTrxResultMapper;
use App\Application\UserContract\Mutator\UserContractInvestmentMutator;
use App\Application\UserContract\Transformer\UserContractClaimEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Investment\UserContractInvestmentClaim;
use App\Persistence\PersistorInterface;

class ClaimInvestmentService
{
    public function __construct(
        private readonly ClaimOperation $claimOperation,
        private readonly UserContractInvestmentMutator $userContractInvestmentMutator,
        private readonly PersistorInterface $persistor,
        private readonly UserInvestmentTrxResultMapper $userInvestmentTrxResultMapper,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly UserContractClaimEntityTransformer $userContractClaimEntityTransformer,
        private readonly ScContractResultBuilder $scContractResultBuilder

    ){}

    public function claimInvestment(UserContractInvestmentClaim $userContractInvestmentClaim): void
    {
        try{
            $userContractInvestment = $userContractInvestmentClaim->getUserContractInvestment();
            $transactionResponse = $this->claimOperation->claim($userContractInvestment);
            $trxLedger =  $transactionResponse->getLatestLedger() ?? $transactionResponse->getLedger();
            $trxResult = $this->scContractResultBuilder->getResultData($transactionResponse);
            $this->userInvestmentTrxResultMapper->mapToEntity($trxResult, $userContractInvestment);
            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $userContractInvestment->getContract()->getAddress(),
                'Investment',
                'claim',
                $trxResult,
                $transactionResponse->getTxHash(),
                $trxLedger
            );

            $claimedAt = new \DateTimeImmutable(date('Y-m-d H:i:s', $trxLedger));
            $this->userContractInvestmentMutator->updateUserContractInvestmentWithNewClaim($userContractInvestment, $claimedAt);
            $this->userContractClaimEntityTransformer->updateInvestmentClaimWithSuccessfulTransactionResult(
                $userContractInvestmentClaim,
                $transactionResponse->getTxHash(),
                $userContractInvestment->getRegularPayment(),
                $claimedAt
            );

            $userContractInvestmentClaim->setTransaction($contractTransaction);
            $this->persistor->persist([$contractTransaction, $userContractInvestment, $userContractInvestmentClaim]);
        }
        catch(TransactionExceptionInterface $ex) {
            $userContractInvestmentClaim->setStatus($ex->getStatus());
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $userContractInvestmentClaim->getUserContractInvestment()->getContract()->getAddress(),
                'Investment',
                'claim',
                $ex->getError(),
                $ex->getHash(),
                $ex->getFailureLedger()
            );

            $userContractInvestmentClaim->setTransaction($contractTransaction);
            $this->persistor->persist([$contractTransaction, $userContractInvestmentClaim]);

        }

        
    }
}
