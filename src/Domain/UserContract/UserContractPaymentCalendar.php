<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
