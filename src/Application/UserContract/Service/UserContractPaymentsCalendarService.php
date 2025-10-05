<?php

namespace App\Application\UserContract\Service;

use App\Domain\UserContract\Service\UserContractPaymentCalendarBuilder;
use App\Domain\UserContract\UserContractPaymentCalendar;
use App\Entity\Contract\UserContract;
use App\Persistence\UserContract\UserContractPaymentStorageInterface;

class UserContractPaymentsCalendarService
{
    public function __construct(
        private readonly UserContractPaymentStorageInterface $userContractPaymentStorage,
        private readonly UserContractPaymentCalendarBuilder $userContractPaymentCalendarBuilder,
    ) {
    }

    public function generatePaymentsCalendar(UserContract $userContract): UserContractPaymentCalendar
    {
        $payments = $this->userContractPaymentStorage->getTransferredPaymentsByUserContract($userContract);
        $paymentDates = [];
        foreach ($payments as $payment) {
            $paidAt = $payment->getPaidAt();
            $paymentDates[$paidAt->format('Y-m')] = $paidAt->format('Y-m-d H:i');
        }

        return $this->userContractPaymentCalendarBuilder->buildCalendar($userContract, $paymentDates);
    }
}
