<?php

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
