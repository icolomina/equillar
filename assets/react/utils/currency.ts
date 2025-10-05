/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
import { TokenContract } from "../model/token";

export function formatCurrency(value: number, code: string, locale?: string, fiatReference?: string): string {
    if(locale && fiatReference) {
        return Intl.NumberFormat(locale, {
            style: 'currency',
            currency: fiatReference,
        }).format(value)
    }
    else {
        return value + ' ' + code;
    }
}

export function formatCurrencyFromValueAndTokenContract(value: number, tokenContract: TokenContract): string {
    if(tokenContract.locale && tokenContract.fiatReference) {
        return Intl.NumberFormat(tokenContract.locale, {
            style: 'currency',
            currency: tokenContract.fiatReference,
        }).format(value)
    }
    else {
        return value + ' ' + tokenContract.code;
    }
}