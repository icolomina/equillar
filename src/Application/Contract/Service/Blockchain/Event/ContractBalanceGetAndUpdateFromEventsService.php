<?php

namespace App\Application\Contract\Service\Blockchain\Event;

use App\Application\Contract\Mapper\GetContractBalanceMapper;
use App\Application\Contract\Transformer\ContractBalanceEntityTransformer;
use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\EventsRequestTransactionException;
use App\Blockchain\Stellar\Soroban\ScContract\Event\GetContractBalanceUpdatedEvents;
use App\Domain\Contract\ContractNames;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Contract\Contract;
use App\Persistence\PersistorInterface;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class ContractBalanceGetAndUpdateFromEventsService
{
    public function __construct(
        private readonly GetContractBalanceUpdatedEvents $getContractBalanceUpdatedEvents,
        private readonly GetContractBalanceMapper $getContractBalanceMapper,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly ContractBalanceEntityTransformer $contractBalanceEntityTransformer,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly ContractEntityTransformer $contractEntityTransformer,
        private readonly PersistorInterface $persistor,
    ) {
    }

    public function getContractBalanceEvents(Contract $contract, int $startLedger): void
    {
        $contractBalance = null;

        try {
            $contractBalance = $this->contractBalanceEntityTransformer->fromContractInvestmentToBalance($contract);
            $eventsResponse = $this->getContractBalanceUpdatedEvents->getContractBalanceUpdatedEvents($contract, $startLedger);
            if (0 === count($eventsResponse->events)) {
                // log
                return;
            }

            $lastEvent = end($eventsResponse->events);
            $xdrScVal = XdrSCVal::fromBase64Xdr($lastEvent->value);
            $trxResult = $this->scContractResultBuilder->getResultDataFromXdrResult($xdrScVal, $lastEvent->txHash);

            $this->getContractBalanceMapper->mapToEntity($trxResult, $contractBalance);
            $contractTransaction = $this->contractTransactionEntityTransformer->fromEventInfo($contract->getAddress(), ContractNames::INVESTMENT->value, $trxResult, $lastEvent);

            $this->contractBalanceEntityTransformer->updateContractBalanceAsConfirmed($contractBalance, $contractTransaction);
            $this->persistor->persist([$contractTransaction, $contractBalance]);

            if ($contractBalance->getFundsReceived() >= $contract->getGoal()) {
                $this->contractEntityTransformer->updateContractAsFundsReached($contract);
                $this->persistor->persist($contract);
            }

            $this->persistor->flush();
        } catch (EventsRequestTransactionException $er) {
            $contractBalance->setStatus('FAILED');
            $contractTransaction = $this->contractTransactionEntityTransformer->fromBadEventRequest($contract->getAddress(), ContractNames::INVESTMENT->value, $er->getMessage());
            // $this->persistor->persist([$contractTransaction, $contractBalance]);
        }
    }
}
