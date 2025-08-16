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