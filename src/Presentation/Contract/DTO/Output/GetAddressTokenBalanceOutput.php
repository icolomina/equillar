<?php

namespace App\Presentation\Contract\DTO\Output;

readonly class GetAddressTokenBalanceOutput
{
    public function __construct(
        public string $balance
    ){}
}
