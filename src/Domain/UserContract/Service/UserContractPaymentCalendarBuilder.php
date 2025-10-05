<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\UserContract\Service;

use App\Domain\Contract\ContractReturnType;
use App\Domain\UserContract\UserContractPaymentCalendar;
use App\Domain\UserContract\UserContractPaymentCalendarItem;
use App\Entity\Contract\UserContract;

class UserContractPaymentCalendarBuilder
{
    public function buildCalendar(UserContract $userContract, array $transferredPayments): UserContractPaymentCalendar
    {
        $calendar = new UserContractPaymentCalendar();
        $claimableAt = new \DateTime(date('Y-m-d H:i:s', $userContract->getClaimableTs()));
        $returnMonths = $userContract->getContract()->getReturnMonths();
        $transferredPaymentDates = array_keys($transferredPayments);

        for ($i = 1; $i <= $returnMonths; ++$i) {
            $total = $userContract->getRegularPayment();
            if ($userContract->getContract()->getReturnType() === ContractReturnType::COUPON->value && $i === $returnMonths) {
                $total += $userContract->getBalance();
            }

            if ($i > 1) {
                $claimableAt->add(\DateInterval::createFromDateString('+ 1 month'));
            }

            $calendarItem = new UserContractPaymentCalendarItem($claimableAt->format('Y-m'), $total);

            if (in_array($claimableAt->format('Y-m'), $transferredPaymentDates)) {
                $calendarItem->isTransferred = true;
                $calendarItem->transferredAt = $transferredPayments[$claimableAt->format('Y-m')];
            } else {
                $calendarItem->willBeTransferredAt = $claimableAt->format('Y-m-d');
            }

            $calendar->addToCalendar($calendarItem);
        }

        return $calendar;
    }
}
