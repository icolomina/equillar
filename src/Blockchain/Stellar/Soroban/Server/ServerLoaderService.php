<?php

namespace App\Blockchain\Stellar\Soroban\Server;

use App\Stellar\Networks;
use Soneso\StellarSDK\Network;
use Soneso\StellarSDK\Soroban\Responses\GetHealthResponse;
use Soneso\StellarSDK\Soroban\SorobanServer;

class ServerLoaderService
{
    private ?Networks $network = null;

    public function __construct(
        //private readonly string $sorobanServer
    ){}

    public function getServer(): SorobanServer
    {
        $this->network = Networks::TESTNET;
        /*if(!$this->network) {
            throw new \LogicException('Unknown Soroban Network: ' . $this->sorobanServer);
        }*/

        $server = new SorobanServer($this->network->value);
        $healthResponse = $server->getHealth();
        if (GetHealthResponse::HEALTHY != $healthResponse->status) {
            throw new \RuntimeException(sprintf('Soroban server "%s" is not available', $this->network->value));
        }

        return $server;
    }

    public function getSorobanNetwork(): Network
    {
        if(!$this->network){
            throw new \LogicException('Network empty. Try to getServer first');
        }
        
        return match($this->network) {
            Networks::TESTNET => Network::testnet(),
            default => Network::public()
        };
    }

    public function getNetwork(): ?Networks
    {
        return $this->network;
    }
}
