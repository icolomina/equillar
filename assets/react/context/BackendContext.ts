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

