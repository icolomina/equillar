import { FREIGHTER_ID, FreighterModule, ISupportedWallet, StellarWalletsKit, WalletNetwork, xBullModule } from "@creit.tech/stellar-wallets-kit";
import { useEffect, useState } from "react";

export interface Wallet {
    wallet: StellarWalletsKit,
    walletConnected: boolean,
    connectWallet: () => void,
    disconnectWallet: () => void
}

export const useStellarWallet = (network: WalletNetwork): Wallet => {
    const [wallet, setWallet] = useState<StellarWalletsKit>(null);
    const [walletConnected, setWalletConnected] = useState<boolean>(false);
    
    useEffect(
        () => {
            const kit: StellarWalletsKit = new StellarWalletsKit({
                network: network,
                selectedWalletId: FREIGHTER_ID,
                modules: [
                  new FreighterModule(),
                ]
            });
            setWallet(kit);
        }, []
    )
    

    const connectWallet = async() => {
        await wallet.openModal({
            onWalletSelected: async (option: ISupportedWallet) => {
              wallet.setWallet(option.id);
              setWalletConnected(true);
            }
        });
    }

    const disconnectWallet = () => {
        setWallet(null);
        setWalletConnected(false);
    }

    return {
        wallet,
        walletConnected,
        connectWallet,
        disconnectWallet
    } as Wallet
}