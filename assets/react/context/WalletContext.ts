import { createContext } from "react";
import { Wallet } from "../hooks/Wallet/StellarWalletsHook";

export const WalletContext = createContext<Wallet>(null);