<?php

namespace App\Blockchain\Stellar\Soroban\Server;

use App\Application\SystemWallet\Service\RetrieveSystemWalletService;
use Soneso\StellarSDK\Network;
use Soneso\StellarSDK\Soroban\Responses\GetHealthResponse;
use Soneso\StellarSDK\Soroban\SorobanServer;

class ServerLoaderService
{
    public function __construct(
        private readonly RetrieveSystemWalletService $retrieveSystemWalletService
    ){}

    public function getServer(): SorobanServer
    {
        $systemWalletData  = $this->retrieveSystemWalletService->retrieve();

        $server = new SorobanServer($systemWalletData->url);
        $healthResponse = $server->getHealth();
        if (GetHealthResponse::HEALTHY != $healthResponse->status) {
            throw new \RuntimeException(sprintf('Soroban server "%s" is not available', $systemWalletData->url));
        }

        return $server;
    }

    public function getSorobanNetwork(): Network
    {       
        $systemWalletData  = $this->retrieveSystemWalletService->retrieve();
        return ($systemWalletData->isTest) 
            ? Network::testnet()
            : Network::public()
        ;
    }

    public function getSorobanRpcUrl(): string 
    {
        $systemWalletData  = $this->retrieveSystemWalletService->retrieve();
        return $systemWalletData->url;
    }
}
