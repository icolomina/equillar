<?php

namespace App\Domain\SystemWallet;

use App\Domain\Crypt\CryptedValue;

readonly class SystemWalletData
{
    public function __construct(
        public string $address,
        public string $blockchain,
        public string $network,
        public string $url,
        public bool $isTest,
        public CryptedValue $cryptedValue
    ){}
}
