<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
