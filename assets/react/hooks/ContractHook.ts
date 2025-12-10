// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Address, Contract, ScInt, SorobanRpc, TransactionBuilder } from "@stellar/stellar-sdk";
import { useCallback, useContext, useRef } from "react";
import { ContractOutput } from "../model/contract";
import { StellarWalletsKit } from "@creit.tech/stellar-wallets-kit";
import { Api } from "@stellar/stellar-sdk/lib/rpc";
import { BackendContext, BackendContextData, getSorobanNetwork } from "../context/BackendContext";
import { parseAmount } from "../utils/token";

export interface ContractHook {
    init: (url: string) => Promise<SorobanStatus>,
    investOnProject: (
        wallet: StellarWalletsKit,
        contract: ContractOutput,
        amount: string
      ) => Promise<Api.SendTransactionResponse>;
}

export enum SorobanStatus {
    HEALTHY,
    UNAVAILABLE
}

export const useContract = () => {

    const baseFee = '100';
    const serverRef = useRef<SorobanRpc.Server>();
    const sorobanCtx: BackendContextData = useContext(BackendContext);

    const init = useCallback(async (url: string): Promise<SorobanStatus> => {
        if (serverRef.current) {
            return SorobanStatus.HEALTHY;
        }

        serverRef.current = new SorobanRpc.Server(url);
        const healthResponse = await serverRef.current.getHealth();
        return (healthResponse.status !== 'healthy')
            ? SorobanStatus.UNAVAILABLE
            : SorobanStatus.HEALTHY
        ;

    }, []);

    const investOnProject = async(wallet: StellarWalletsKit, contract: ContractOutput, amount: string): Promise<Api.SendTransactionResponse> => {

        const { address } = await wallet.getAddress();
        const account = await serverRef.current.getAccount(address);
        const c = new Contract(contract.address);
        const amountScVal = new ScInt(parseAmount(amount, contract.tokenContract.decimals).toString()).toI128();
        const scAddress = new Address(address).toScVal();

        const tx = new TransactionBuilder(account, { fee: baseFee, networkPassphrase: sorobanCtx.sorobanNetworkPassphrase })
            .addOperation(c.call('invest', scAddress, amountScVal))
            .setTimeout(30)
            .build()
            ;
            
        const simulatedTx = await serverRef.current.simulateTransaction(tx);
        const readyTx     = SorobanRpc.assembleTransaction(tx, simulatedTx).build();
        
        const { signedTxXdr } = await wallet.signTransaction(readyTx.toXDR(), {
            address,
            networkPassphrase: getSorobanNetwork(sorobanCtx)
        });

        return serverRef.current.sendTransaction(TransactionBuilder.fromXDR(signedTxXdr, sorobanCtx.sorobanNetworkPassphrase));
    }

    return {
        init,
        investOnProject
    } as ContractHook;
}