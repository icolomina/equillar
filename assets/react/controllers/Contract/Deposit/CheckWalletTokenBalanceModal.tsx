/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { StellarWalletsKit } from "@creit.tech/stellar-wallets-kit";
import { useQuery } from "@tanstack/react-query"
import { FC, Fragment, useState } from "react"
import { useApi } from "../../../hooks/ApiHook";
import { ContractOutput } from "../../../model/contract";
import axios, { AxiosError, AxiosResponse } from "axios";
import { Box, Button, CircularProgress, Modal, Typography } from "@mui/material";
import WarningAmberIcon from '@mui/icons-material/WarningAmber';
import CheckCircleIcon from '@mui/icons-material/CheckCircle';
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import { StyledModalBox } from "../../Theme/Styled/Modal";
import { formatCurrencyFromValueAndTokenContract } from "../../../utils/currency";

export interface CheckWalletTokenBalanceModalProps {
    openModal: boolean,
    handleCloseModal: () => void,
    handleOnBalanceRetrieved: (balance: string) => void,
    wallet: StellarWalletsKit
    contract: ContractOutput
}

interface Balance {
    balance: string;
}

interface Address {
    address: string;
}

export default function CheckWalletTokenBalanceModal({ openModal, handleCloseModal, handleOnBalanceRetrieved, wallet, contract }: CheckWalletTokenBalanceModalProps) {

    const { callGet } = useApi();
    const routes = useApiRoutes();
    const [balanceRetrieved, setBalanceRetrieved] = useState<string>();

    const query = useQuery(
        {
            queryKey: ['get-wallet-token-balance', contract.tokenContract.code],
            queryFn: async () => {
                const addr: Address = await wallet.getAddress();
                const result: AxiosResponse<Balance> | AxiosError = await callGet<object, Balance>(routes.getContractBalance(contract.id), { address: addr.address });
                if (axios.isAxiosError(result)) {
                    throw new Error(result.message);
                }

                setBalanceRetrieved(result.data.balance);
                handleOnBalanceRetrieved(result.data.balance);
                return result.data;

            },
            enabled: openModal,
            retry: 0,
        }
    );

    return (
        <Modal open={openModal} onClose={handleCloseModal}>
            <StyledModalBox width={400} useFlex={true}>
                {query.isLoading ? (
                    <Fragment>
                        <Typography sx={{ mb: 1, textAlign: 'center' }}>
                            Checking {contract.tokenContract.code} balance
                        </Typography>
                        <CircularProgress />
                    </Fragment>
                ) : (
                    <Fragment>
                        {parseFloat(balanceRetrieved) < contract.minPerInvestment ? (
                            <Fragment>
                                <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
                                    <WarningAmberIcon sx={{ color: 'orange', mr: 1, fontSize: 30 }} />
                                    <Typography variant="h6" component="h2" sx={{ color: 'orange' }}>
                                        Insufficient Balance
                                    </Typography>
                                </Box>
                                <Typography sx={{ mt: 2 }}>
                                    You do not have a sufficient balance in your wallet to make this investment. The minimum balance required is 
                                    {formatCurrencyFromValueAndTokenContract(contract.minPerInvestment, contract.tokenContract)}
                                </Typography>
                                <Button onClick={handleCloseModal} sx={{ mt: 2 }}>
                                    Close
                                </Button>
                            </Fragment>
                        ) : (
                            <Fragment>
                                <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
                                    <CheckCircleIcon sx={{ color: 'green', mr: 1, fontSize: 30 }} />
                                    <Typography variant="h6" component="h2" sx={{ color: 'green' }}>
                                        You can invest {String.fromCodePoint(0x1F60A)}
                                    </Typography>
                                </Box>
                                <Typography sx={{ mt: 2 }}>
                                        You have enough {contract.tokenContract.code} balance in your wallet.
                                </Typography>
                                <Typography sx={{ mt: 2 }}>
                                    Available balance: <strong>{formatCurrencyFromValueAndTokenContract(parseFloat(balanceRetrieved), contract.tokenContract)}</strong>
                                </Typography>
                                <Button onClick={handleCloseModal} sx={{ mt: 2 }}>
                                    Close
                                </Button>
                            </Fragment>
                        )}
                    </Fragment>
                )}
            </StyledModalBox>
        </Modal>
    )
}