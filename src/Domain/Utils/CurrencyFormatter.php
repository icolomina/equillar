<?php

namespace App\Domain\Utils;

class CurrencyFormatter
{
    private ?\NumberFormatter $numberFormatter = null;

    public function loadFormatter(string $locale): void
    {
        $this->numberFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
    }

    public function formatCurrency(float $amount, string $currencyIso): string
    {
        return $this->numberFormatter->formatCurrency($amount, $currencyIso);
    }
}
