/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { allowAllModules, FREIGHTER_ID, StellarWalletsKit, WalletNetwork } from "@creit.tech/stellar-wallets-kit";
import { useState } from "react";

export interface Wallet {
    getWallet: () => StellarWalletsKit,
    generateButton: (element: string) => void

}

export const useStellarWallet = (network: WalletNetwork): Wallet => {
    const [wallet, setWallet] = useState<StellarWalletsKit>(null);

    const generateButton = async(element: string) => {

        const kit: StellarWalletsKit = new StellarWalletsKit({
            network: network,
            selectedWalletId: FREIGHTER_ID,
            modules: allowAllModules(),
        });

        await kit.createButton({
            container: document.querySelector('#' + element),
            buttonText: "Connect your wallet",
            onConnect: ({ address }) => {
                setWallet(kit);
            },
            onDisconnect: () => {
                setWallet(null);
            }
          })
    }

    const getWallet = (): StellarWalletsKit => {
        return wallet
    }

    return {
        getWallet,
        generateButton
    } as Wallet
}