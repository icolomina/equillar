<?php

namespace App\Presentation\UserContract\DTO\Output;

class UserContractPaymentDtoOutput
{
    public function __construct(
        public readonly string $id,
        public readonly string $projectIssuer,
        public readonly string $projectName,
        public readonly ?string $hash,
        public readonly string $paymentEmittedAt,
        public readonly string $totalToReceive,
        public readonly string $status,
        public readonly ?string $paymentPaidAt = null,
        public string $totalReceived = 'Reception pending',
    ){}
}
