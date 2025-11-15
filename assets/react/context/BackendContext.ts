// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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

