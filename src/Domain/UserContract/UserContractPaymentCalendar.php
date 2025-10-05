<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\UserContract;

class UserContractPaymentCalendar
{
    /**
     * @var UserContractPaymentCalendarItem[]
     */
    private array $calendar;

    public function __construct()
    {
        $this->calendar = [];
    }

    public function addToCalendar(UserContractPaymentCalendarItem $item): void
    {
        $this->calendar[] = $item;
    }

    public function getCalendar(): array
    {
        return $this->calendar;
    }
}
