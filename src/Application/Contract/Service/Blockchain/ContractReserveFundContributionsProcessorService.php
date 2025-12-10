<?php

namespace App\Application\Contract\Service\Blockchain;

use App\Application\Contract\Transformer\ContractReserveFundContributionTransformer;
use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Domain\Contract\ContractProcessIncomingContributionsResult;
use App\Persistence\Contract\ContractReserveFundContributionStorageInterface;
use App\Persistence\Contract\ContractStorageInterface;
use App\Persistence\PersistorInterface;
use Soneso\StellarSDK\Responses\Operations\PaymentOperationResponse;

class ContractReserveFundContributionsProcessorService
{
    public function __construct(
        private readonly StellarAccountLoader $stellarAccountLoader,
        private readonly ContractReserveFundContributionTransferService $contractReserveFundContributionTransferService,
        private readonly ContractReserveFundContributionTransformer $contractReserveFundContributionTransformer,
        private readonly ContractReserveFundContributionStorageInterface $contractReserveFundContributionStorage,
        private readonly ContractStorageInterface $contractStorage,
        private readonly PersistorInterface $persistor
    ) {}

    /**
     * @return array<string, ContractProcessIncomingContributionsResult>
     */
    public function processIncomingContributons(): array
    {
        $contributionsResult = [];
        $sdk = $this->stellarAccountLoader->getSdk();
        $operationsResponse = $sdk
            ->payments()
            ->includeTransactions(true)
            ->forAccount($this->stellarAccountLoader->getAccount()->getAccountId())
            ->order('desc')
            ->limit(10)
            ->execute()
        ;

        foreach ($operationsResponse->getOperations() as $payment) {
            if (!$payment->isTransactionSuccessful()) {
                $contributionsResult[$payment->getTransactionHash()] = ContractProcessIncomingContributionsResult::fromInvalidTransaction();
                continue;
            }

            if(!$payment instanceof PaymentOperationResponse) {
                continue;
            }

            $sourceAccount = $payment->getSourceAccount();
            $destinationMuxedAccount = $payment->getToMuxed();

            if (!$destinationMuxedAccount) {
                $contributionsResult[$payment->getTransactionHash()] = ContractProcessIncomingContributionsResult::fromEmptyDestinationMuxedAccount();
                continue;
            }

            $contract = $this->contractStorage->getContractByMuxedAccount($destinationMuxedAccount);

            if ($contract->getProjectAddress() !== $sourceAccount) {
                $contributionsResult[$payment->getTransactionHash()] = ContractProcessIncomingContributionsResult::fromUnmatchingSourceAccountAndProjectAddress();
                continue;
            }

            $existingContribution = $this->contractReserveFundContributionStorage->getByPaymentTransactionHash($payment->getTransactionHash());
            if ($existingContribution) {
                $contributionsResult[$payment->getTransactionHash()] = ContractProcessIncomingContributionsResult::fromContributionAlreadyProcessed();
                continue;
            }

            $tokenBalance = $this->stellarAccountLoader->getTokenBalance($contract->getToken());
            if($tokenBalance < (float) $payment->getAmount()) {
                $contributionsResult[$payment->getTransactionHash()] = ContractProcessIncomingContributionsResult::fromSystemAddressNotHoldingEnougthBalance();
                continue;
            }

            $contractReserveFundContribution = $this->contractReserveFundContributionTransformer->fromContractAndAmountToEntity(
                $contract,
                $payment->getTransactionHash(),
                (float) $payment->getAmount()
            );

            $this->persistor->persistAndFlush($contractReserveFundContribution);
            $this->contractReserveFundContributionTransferService->processReserveFundContribution($contractReserveFundContribution);
            $contributionsResult[$payment->getTransactionHash()] = ContractProcessIncomingContributionsResult::fromProcessed();
        }

        return $contributionsResult;
    }
}
