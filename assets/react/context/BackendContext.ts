/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { WalletNetwork } from "@creit.tech/stellar-wallets-kit";
import { createContext } from "react";

export interface BackendContext {
  sorobanNetworkPassphrase?: string,
  sorobanRpcUrl?: string
  webserverEndpoint?: string
}

export interface BackendContextData {
  sorobanNetworkPassphrase?: string
  sorobanRpcUrl?: string,
  webserverEndpoint?: string
}

export const BackendContext = createContext<BackendContext | null>(null);
export const getSorobanNetwork = (ctx: BackendContext) => {
  return ctx.sorobanNetworkPassphrase === WalletNetwork.TESTNET
    ? WalletNetwork.TESTNET
    : WalletNetwork.PUBLIC
  ;
} 

