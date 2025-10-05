
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { useApi } from "../../../hooks/ApiHook";
import { ContractOutput, ContractReserveFundContributionRequestResult } from "../../../model/contract";
import { useState } from "react";
import { Box, Button, CircularProgress, Modal, TextField, Typography, useMediaQuery, useTheme } from "@mui/material";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import { AxiosResponse } from "axios";
import IconButton from '@mui/material/IconButton';
import ContentCopyIcon from '@mui/icons-material/ContentCopy';

interface ReserveFundContributionModalProps {
    open: boolean;
    contract: ContractOutput;
    onClose: () => void;
}

export default function CreateReserveFundContributionModal(props: ReserveFundContributionModalProps) {

    const { callPost } = useApi();
    const apiRoutes = useApiRoutes();
    const theme = useTheme();

    const [requestedAmount, setRequestedAmount] = useState<string>('');
    const [error, setError] = useState('');
    const [requestProcessData, setRequestProcessData] = useState<ContractReserveFundContributionRequestResult>(null);
    const [requestingReserveFundContribution, setRequestingReserveFundContribution] = useState<boolean>(false);

    const isMobile = useMediaQuery(theme.breakpoints.down('sm'));

    const handleInputChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        const value = event.target.value;
        setRequestedAmount(value);
        setError('');
    };

    const handleSubmit = () => {
        const amount = Number(requestedAmount);
        if (isNaN(amount) || amount <= 0) {
            setError('Please, enter a valid amount');
            return;
        }

        setRequestingReserveFundContribution(true);
        callPost<object, ContractReserveFundContributionRequestResult>(apiRoutes.requestReserveFundContribution(props.contract.id), { amount: amount }).then(
            (result: AxiosResponse<ContractReserveFundContributionRequestResult>) => {
                setRequestProcessData(result.data);
                setRequestingReserveFundContribution(false);
            }
        )
    };

    return (
        <Modal
            open={props.open}
            aria-labelledby="modal-modal-title"
            aria-describedby="modal-modal-description"
            sx={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
            }}
        >
            <Box
                sx={{
                    bgcolor: 'background.paper',
                    borderRadius: 1,
                    boxShadow: 24,
                    p: 4,
                    width: isMobile ? '90%' : 600, // Mayor ancho en escritorio, adaptable en móvil
                    maxWidth: 600, // Ancho máximo para pantallas grandes
                    display: 'flex',
                    flexDirection: 'column',
                    alignItems: 'center',
                }}
            >
                <Typography id="modal-modal-title" variant="h5" component="h2" mb={2} textAlign="center">
                    Request Reserve Fund Contribution
                </Typography>

                {requestingReserveFundContribution ? (
                    <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', mt: 2 }}>
                        <CircularProgress />
                        <Typography sx={{ mt: 1 }}>Processing. Please wait a moment...</Typography>
                    </Box>
                ) : requestProcessData ? (
                        <>
                            <Typography color="success" variant="h6" sx={{ mt: 2, textAlign: 'center' }}>
                                Reserve fund contribution registered successfully
                            </Typography>
                            <Typography variant="body1" sx={{ mt: 2, textAlign: 'center' }}>
                                In order to complete the contribution, send the funds to the following address:
                            </Typography>
                            <Box sx={{
                                display: 'flex',
                                alignItems: 'center',
                                mt: 1,
                                p: 1,
                                bgcolor: 'grey.200',
                                borderRadius: 1,
                                width: '100%',
                            }}>
                                <Typography variant="body2" sx={{
                                    flexGrow: 1,
                                    wordBreak: 'break-all',
                                    textAlign: 'left'
                                }}>
                                    {requestProcessData.destinationAddress}
                                </Typography>
                                <IconButton
                                    aria-label="copy address"
                                    onClick={() => navigator.clipboard.writeText(requestProcessData.destinationAddress)}
                                    sx={{ ml: 1 }}
                                >
                                    <ContentCopyIcon />
                                </IconButton>
                            </Box>
                            <Typography variant="body1" sx={{ mt: 2, textAlign: 'center' }}>
                                It is mandatory that you include the next identifier as a memo field in the transaction:
                            </Typography>
                            <Box sx={{
                                display: 'flex',
                                alignItems: 'center',
                                mt: 1,
                                p: 1,
                                bgcolor: 'grey.200',
                                borderRadius: 1,
                                width: '100%',
                            }}>
                                <Typography variant="body2" sx={{
                                    flexGrow: 1,
                                    wordBreak: 'break-all',
                                    textAlign: 'left'
                                }}>
                                    {requestProcessData.contributionId}
                                </Typography>
                                <IconButton
                                    aria-label="copy contribution id"
                                    onClick={() => navigator.clipboard.writeText(requestProcessData.contributionId)}
                                    sx={{ ml: 1, color: 'primary.main' }}
                                >
                                    <ContentCopyIcon />
                                </IconButton>
                            </Box>
                            <Typography variant="caption" sx={{ mt: 1, textAlign: 'center', color: 'text.secondary' }}>
                                Without this identifier, we cannot match the pay with your request.
                            </Typography>
                            <Button variant="outlined" color="primary" onClick={() => props.onClose()} sx={{ mt: 3 }}>
                                Close
                            </Button>
                        </>
                ) : (
                    <>
                        <TextField
                            label="Amount to contribute with"
                            type="number"
                            fullWidth
                            value={requestedAmount}
                            onChange={handleInputChange}
                            error={!!error}
                            helperText={error}
                            sx={{ mb: 2 }}
                        />
                        <Box sx={{ display: 'flex', justifyContent: 'flex-end', width: '100%' }}>
                            <Button onClick={() => props.onClose()} sx={{ mr: 1 }}>
                                Cancel
                            </Button>
                            <Button
                                variant="contained"
                                color="primary"
                                onClick={handleSubmit}
                                disabled={!requestedAmount || requestingReserveFundContribution}
                            >
                                Request
                            </Button>
                        </Box>
                    </>
                )}
            </Box>
        </Modal>
    );
}