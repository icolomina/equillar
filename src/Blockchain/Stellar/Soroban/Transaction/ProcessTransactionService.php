<?php

namespace App\Blockchain\Stellar\Soroban\Transaction;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Blockchain\Stellar\Exception\Transaction\GetTransactionException;
use App\Blockchain\Stellar\Exception\Transaction\SimulatedTransactionException;
use App\Blockchain\Stellar\Soroban\Server\ServerLoaderService;
use App\Blockchain\Stellar\Exception\Transaction\SendTransactionException;
use Soneso\StellarSDK\AbstractOperation;
use Soneso\StellarSDK\Crypto\KeyPair;
use Soneso\StellarSDK\Soroban\Requests\SimulateTransactionRequest;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;
use Soneso\StellarSDK\Soroban\Responses\SendTransactionResponse;
use Soneso\StellarSDK\Soroban\Responses\SimulateTransactionResponse;
use Soneso\StellarSDK\Soroban\SorobanServer;
use Soneso\StellarSDK\Transaction;
use Soneso\StellarSDK\TransactionBuilder;
use Symfony\Component\DependencyInjection\Attribute\Lazy;

#[Lazy]
class ProcessTransactionService
{
    const int MAX_ITERATIONS = 10;
    const int DEFAULT_WAITING_SLEEP = 1; // in seconds

    private SorobanServer $server;

    public function __construct(
        private readonly ServerLoaderService $serverLoaderService,
        private readonly StellarAccountLoader $stellarAccountLoader
    ){
        $this->server = $this->serverLoaderService->getServer();
    }

    public function sendTransaction(AbstractOperation $operation, bool $addAuth = false, ?KeyPair $invoker = null): ?GetTransactionResponse
    {
        $this->stellarAccountLoader->load();

        $transaction = (new TransactionBuilder($this->stellarAccountLoader->getAccount()))->addOperation($operation)->build();

        $request = new SimulateTransactionRequest($transaction);
        $simulateResponse = $this->server->simulateTransaction($request);

        if($simulateResponse->resultError || $simulateResponse->getError()) {
            throw new SimulatedTransactionException($simulateResponse);
        }

        $transactionData = $simulateResponse->transactionData;
        $minResourceFee = $simulateResponse->minResourceFee;

        $transaction->setSorobanTransactionData($transactionData);
        $transaction->addResourceFee($minResourceFee);
        if($addAuth) {
            $this->addSorobanAuthenticationEntries($transaction, $simulateResponse, $invoker);
        }

        $transaction->sign($this->stellarAccountLoader->getKeyPair(), $this->serverLoaderService->getSorobanNetwork());
        $sendTransactionResponse = $this->server->sendTransaction($transaction);

        if ($sendTransactionResponse->status !== SendTransactionResponse::STATUS_PENDING) {
            throw new SendTransactionException($sendTransactionResponse);
        }

        return $this->waitForTransaction($sendTransactionResponse->hash);
    }

    public function waitForTransaction(string $hash, int $maxIterations = self::MAX_ITERATIONS, ?int $microseconds = null) : GetTransactionResponse 
    {
        $counter = 0;
        do{
            ($microseconds > 0)
                ? usleep($microseconds)
                : sleep(self::DEFAULT_WAITING_SLEEP)
            ;

            $transactionResponse = $this->server->getTransaction($hash);
            $status = $transactionResponse->status;
            $counter++;

        } while($counter < $maxIterations && !in_array($status, [GetTransactionResponse::STATUS_SUCCESS, GetTransactionResponse::STATUS_FAILED]));

        if($status !== GetTransactionResponse::STATUS_SUCCESS) {
            throw new GetTransactionException($transactionResponse);
        }

        return $transactionResponse;    
    }

    private function addSorobanAuthenticationEntries(Transaction $transaction, SimulateTransactionResponse $simulateTransactionResponse, ?KeyPair $invoker) : void 
    {
        if($invoker) {
            $auth = $simulateTransactionResponse->getSorobanAuth();
            if(!empty($auth)){
                $latestLedgerResponse = $this->server->getLatestLedger();
                foreach ($auth as $a) {
                    $a->credentials->addressCredentials->signatureExpirationLedger = $latestLedgerResponse->sequence + 10;
                    $a->sign($invoker, $this->serverLoaderService->getSorobanNetwork());
                }

                $transaction->setSorobanAuth($auth);
                return;
            }
        }
        
        $transaction->setSorobanAuth($simulateTransactionResponse->getSorobanAuth());
    }
    
}
