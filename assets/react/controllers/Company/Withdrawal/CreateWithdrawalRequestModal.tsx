// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Box, Button, CircularProgress, Modal, TextField, Typography, useMediaQuery, useTheme } from "@mui/material";
import { useState } from "react";
import { useApi } from "../../../hooks/ApiHook";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import { ContractOutput } from "../../../model/contract";
import { formatCurrencyFromValueAndTokenContract } from "../../../utils/currency";

interface WithdrawalModalProps {
    open: boolean;
    contract: ContractOutput;
    onClose: () => void;
}

export default function CreateWithdrawalRequestModal(props: WithdrawalModalProps) {

    const { callPost } = useApi();
    const apiRoutes = useApiRoutes();
    const theme = useTheme();

    const [requestedAmount, setRequestedAmount] = useState<string>('');
    const [error, setError] = useState('');
    const [successMessage, setSuccessMessage] = useState('');
    const [requestingWithdrawal, setRequestingWithdrawal] = useState<boolean>(false);

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
        if (amount > props.contract.contractBalance.available) {
            setError('The amount to withdraw cannot be greater than the available balance.');
            return;
        }

        setRequestingWithdrawal(true);
        callPost(apiRoutes.requestWithdrawal(props.contract.id), { requestedAmount: amount }).then(
            () => {
                setRequestingWithdrawal(false);
                setSuccessMessage('Withdrawal request successfully submitted. You can view your list of requests through the \'Withdrawal Requests\' option in the side menu');
            }
        )
    };

    return (
        <Modal
            open={props.open}
            //onClose={onClose}
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
                    width: isMobile ? '90%' : 400, 
                    maxWidth: 600, 
                    display: 'flex',
                    flexDirection: 'column',
                    alignItems: 'center',
                }}
            >
                <Typography id="modal-modal-title" variant="h5" component="h2" mb={2} textAlign="center">
                    Request Withdrawal of Funds
                </Typography>
                { successMessage.length === 0 && 
                    <Typography id="modal-modal-description" sx={{ mb: 2, textAlign: 'center' }} >
                        Available balance to withdrawn: <Typography component="span" variant="subtitle1" color="primary" fontWeight="bold">
                            {formatCurrencyFromValueAndTokenContract(props.contract.contractBalance.available, props.contract.tokenContract)}
                        </Typography>
                    </Typography>
                }

                {requestingWithdrawal ? (
                    <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', mt: 2 }}>
                        <CircularProgress />
                        <Typography sx={{ mt: 1 }}>Processing. Please wait a moment...</Typography>
                    </Box>
                ) : successMessage ? (
                    <>
                        <Typography color="success" sx={{ mt: 2, textAlign: 'center' }}>
                            {successMessage}
                        </Typography>
                        <Button variant="outlined" color="primary" onClick={() => props.onClose()}>
                            Close
                        </Button>
                    </>
                ) : (
                    <>
                        <TextField
                            label="Cantidad a retirar"
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
                                disabled={!requestedAmount || requestingWithdrawal}
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