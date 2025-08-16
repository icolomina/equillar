import { useQuery } from "@tanstack/react-query"
import { useApiRoutes } from "../../hooks/ApiRoutesHook";
import axios, { AxiosError, AxiosResponse } from "axios";
import { useApi } from "../../hooks/ApiHook";
import { StellarTransaction } from "../../model/transaction";
import { Box, Button, CircularProgress, Divider, List, ListItem, ListItemIcon, ListItemText, Modal, Stack, Typography } from "@mui/material";
import { StyledModalBox } from "../Theme/Styled/Modal";
import { Fragment } from "react/jsx-runtime";
import { useAuth } from "../../hooks/AuthHook";

import CheckCircleOutlineIcon from '@mui/icons-material/CheckCircleOutline';
import ErrorOutlineIcon from '@mui/icons-material/ErrorOutline';
import DataObjectIcon from '@mui/icons-material/DataObject';
import AttachMoneyIcon from '@mui/icons-material/AttachMoney';
import FingerprintIcon from '@mui/icons-material/Fingerprint';
import LaunchIcon from '@mui/icons-material/Launch';
import CloseIcon from '@mui/icons-material/Close';
import { useContext } from "react";
import { getSorobanNetwork, BackendContext } from "../../context/BackendContext";
import { WalletNetwork } from "@creit.tech/stellar-wallets-kit";


interface EditStellarTransactionDataModalProps {
    hash: string,
    openModal: boolean,
    handleCloseModal: () => void
}

export default function EditStellarTransactionDataModal({ hash, openModal, handleCloseModal }: EditStellarTransactionDataModalProps) {

    const apiRoutes = useApiRoutes();
    const { callGet } = useApi();
    const { isAdmin } = useAuth();

    const backendCtx: BackendContext = useContext(BackendContext);
    const sorobanNetwork = getSorobanNetwork(backendCtx);
    const sorobanNetworkRaw = (sorobanNetwork === WalletNetwork.TESTNET) ? 'testnet' : 'public';

    const query = useQuery({
        queryKey: ['get-stellar-tx', hash],
        queryFn: async () => {
            const result: AxiosResponse<StellarTransaction> | AxiosError = await callGet<object, StellarTransaction>(apiRoutes.getStellarTrxData(hash), {});
            if (axios.isAxiosError(result)) {
                throw new Error(result.message);
            }

            return result.data;
        },
        enabled: openModal,
        retry: 0,
    });

    return (
        <Modal open={openModal} >
            <StyledModalBox width={600} useFlex={true}>
                {query.isLoading ? (
                    <Fragment>
                        <Typography sx={{ mb: 1, textAlign: 'center' }}>
                            Retrieving transaction data
                        </Typography>
                        <CircularProgress />
                    </Fragment>
                ) : (
                    <Fragment>
                        <List sx={{ width: '100%' }}>
                            <ListItem>
                                <ListItemIcon>
                                    {query.data?.isSuccessful ? (
                                        <CheckCircleOutlineIcon color="success" />
                                    ) : (
                                        <ErrorOutlineIcon color="error" />
                                    )}
                                </ListItemIcon>
                                <ListItemText
                                    primary="Estado"
                                    secondary={query.data?.isSuccessful ? "SUCCESS" : "FAILED"}
                                />
                            </ListItem>
                            <ListItem>
                                <ListItemIcon>
                                    <DataObjectIcon />
                                </ListItemIcon>
                                <ListItemText primary="Ledger" secondary={query.data?.ledger || "Unknown"} />
                            </ListItem>
                            {isAdmin() && <ListItem>
                                <ListItemIcon>
                                    <AttachMoneyIcon />
                                </ListItemIcon>
                                <ListItemText primary="Comisión" secondary={query.data?.feeCharged ? query.data.feeCharged + ' XLM' : 'Unknown'} />
                            </ListItem>}
                            <ListItem>
                                <ListItemIcon>
                                    <FingerprintIcon />
                                </ListItemIcon>
                                <ListItemText
                                    primary="Hash"
                                    secondary={query.data?.hash || "Unknown"}
                                    sx={{ wordWrap: 'break-word' }}
                                />
                            </ListItem>
                        </List>
                        <Divider sx={{mt: 3}} />
                        <Box sx={{ display: 'flex', justifyContent: 'center', gap: 2 }}>
                            <Button
                                variant="outlined"
                                onClick={handleCloseModal}
                                startIcon={<CloseIcon />}
                            >
                                Close
                            </Button>

                            <Button
                                variant="contained"
                                startIcon={<LaunchIcon />}
                                href={`https://stellar.expert/explorer/${sorobanNetworkRaw}/tx/${query.data?.hash}`}
                                target="_blank"
                                rel="noopener noreferrer"
                                disabled={!query.data?.hash}
                            >
                                See in stellar explorer
                            </Button>
                        </Box>
                    </Fragment>
                )}
            </StyledModalBox>
        </Modal>
    )
}