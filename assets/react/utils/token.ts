/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
import BigNumber from "bignumber.js";

class TokenValue {
    constructor(
        public whole: string,
        public fraction: string,
        public readonly decimals: number
    ) { }

    toString(): string {
        return this.whole + '.' + this.fraction;
    }

    static fromString(value: string, decimals: number): TokenValue {
        let tokenValue: TokenValue;
        if (value.indexOf('.') < 0) {
            tokenValue = new TokenValue(value, '', decimals);
        }
        else {
            const parts = value.split('.');
            tokenValue = new TokenValue(
                parts[0],
                parts[1],
                decimals
            );
        }


        tokenValue.fraction = tokenValue.fraction.replace(/0+$/, '')
        if (tokenValue.fraction === '') {
            tokenValue.fraction = '0';
        }

        while (tokenValue.fraction.length < tokenValue.decimals) {
            tokenValue.fraction += '0';
        }

        return tokenValue;
    }
}

export function parseAmount(value: string, decimals: number): BigNumber {
    const tokenValue = TokenValue.fromString(value, decimals);
    const wholeValue = new BigNumber(tokenValue.whole);
    const fractionValue = new BigNumber(tokenValue.fraction);

    return wholeValue.shiftedBy(decimals).plus(fractionValue);
}