<?php

namespace App\Application\Contract\Service\Blockchain;

use App\Application\Contract\Service\ReceiveReserveFundContributionService;
use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Domain\Contract\ContractReserveFundContributionStatus;
use App\Domain\Contract\Service\ContractReserveFundContributonIdEncoder;
use App\Domain\Token\Service\TokenNormalizer;
use App\Entity\Contract\ContractReserveFundContribution;
use App\Persistence\Contract\ContractReserveFundContributionStorageInterface;
use App\Presentation\Contract\DTO\Output\ContractCheckReserveFundContributionOutput;
use Soneso\StellarSDK\Memo;
use Soneso\StellarSDK\Responses\Operations\PaymentOperationResponse;

class ContractReserveFundContributionCheckService
{
    public function __construct(
        private readonly StellarAccountLoader $stellarAccountLoader,
        private readonly ContractReserveFundContributonIdEncoder $contractReserveFundContributonIdEncoder,
        private readonly ContractReserveFundContributionStorageInterface $contractReserveFundContributionStorage,
        private readonly TokenNormalizer $tokenNormalizer,
        private readonly ReceiveReserveFundContributionService $receiveReserveFundContributionService
    ) {}

    public function check(ContractReserveFundContribution $contractReserveFundContribution): ContractCheckReserveFundContributionOutput
    {
        $sdk = $this->stellarAccountLoader->getSdk();
        $operationsResponse = $sdk
            ->payments()
            ->includeTransactions(true)
            ->forAccount($this->stellarAccountLoader->getAccount()->getAccountId())
            ->order('desc')
            ->limit(10)
            ->execute()
        ;

        $operationsIterator = $operationsResponse->getOperations();
        $operationsIterator->rewind();
        $payment = null;

        while($operationsIterator->valid() && !$payment) {
            $nextPayment = $operationsIterator->current();
            if(!$nextPayment->isTransactionSuccessful() || !$nextPayment instanceof PaymentOperationResponse) {
                $operationsIterator->next();
                continue;
            }

            $transaction = $nextPayment->getTransaction();
            $memo = $transaction->getMemo();
            if (Memo::MEMO_TYPE_TEXT !== $memo->getType()) {
                $operationsIterator->next();
                continue;
            }

            $decodedId = $this->contractReserveFundContributonIdEncoder->decodeId($memo->getValue());
            if ($decodedId !== $contractReserveFundContribution->getUuid()) {
                $operationsIterator->next();
                continue;
            }

            $payment = $nextPayment;
            $operationsIterator->next();
        }

        if(!$payment) {
            return new ContractCheckReserveFundContributionOutput(ContractReserveFundContributionStatus::CREATED->name);
        }

        $contractReserveFundContribution = $this->contractReserveFundContributionStorage->getByUuidAndStatus($contractReserveFundContribution->getUuid(), 'CREATED');
        $tokenDecimals = $contractReserveFundContribution->getContract()->getToken()->getDecimals();
        $normalizedAmount = $this->tokenNormalizer->normalizeTokenValue($contractReserveFundContribution->getAmount(), $tokenDecimals);

        if ((float) $payment->getAmount() < (float) $normalizedAmount->toPhp($tokenDecimals)) {
            $this->receiveReserveFundContributionService->setReserveFundContributionAsInsufficientFunds($contractReserveFundContribution);
            return new ContractCheckReserveFundContributionOutput(ContractReserveFundContributionStatus::INSUFFICIENT_FUNDS_RECEIVED->name);
        }
        
        $this->receiveReserveFundContributionService->setReserveFundContributionAsReceived($contractReserveFundContribution);
        return new ContractCheckReserveFundContributionOutput(ContractReserveFundContributionStatus::RECEIVED->name);
    
    }
}
