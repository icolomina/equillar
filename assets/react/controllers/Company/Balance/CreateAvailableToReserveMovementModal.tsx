// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { useApi } from "../../../hooks/ApiHook";
import { ContractAvailableToReserveFundMovementCreatedResult, ContractOutput } from "../../../model/contract";
import { useState } from "react";
import { Box, Button, CircularProgress, Modal, TextField, Typography, useMediaQuery, useTheme } from "@mui/material";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import { AxiosResponse } from "axios";

interface CreateAvailableToReserveMovementProps {
    open: boolean;
    contract: ContractOutput;
    onClose: () => void;
}

export default function CreateAvailableToReserveMovementModal(props: CreateAvailableToReserveMovementProps) {

    const { callPost } = useApi();
    const apiRoutes = useApiRoutes();
    const theme = useTheme();

    const [requestedAmount, setRequestedAmount] = useState<string>('');
    const [error, setError] = useState('');
    const [requestProcessData, setRequestProcessData] = useState<ContractAvailableToReserveFundMovementCreatedResult>(null);
    const [requestingBalanceMovement, setRequestingBalanceMovement] = useState<boolean>(false);

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

        if (props.contract.contractBalance.available < amount) {
            setError('You have available less than the requested amount. Choose a lower amount');
            return;
        }

        setRequestingBalanceMovement(true);
        callPost<object, ContractAvailableToReserveFundMovementCreatedResult>(apiRoutes.requestAvailableToReserveFundMovement(props.contract.id), { amount: amount }).then(
            (result: AxiosResponse<ContractAvailableToReserveFundMovementCreatedResult>) => {
                setRequestProcessData(result.data);
                setRequestingBalanceMovement(false);
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
                    width: isMobile ? '90%' : 600, 
                    maxWidth: 600, 
                    display: 'flex',
                    flexDirection: 'column',
                    alignItems: 'center',
                }}
            >
                <Typography id="modal-modal-title" variant="h5" component="h2" mb={2} textAlign="center">
                    Request Movement to the Reserve Fund
                </Typography>

                {requestingBalanceMovement ? (
                    <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', mt: 2 }}>
                        <CircularProgress />
                        <Typography sx={{ mt: 1 }}>Processing. Please wait a moment...</Typography>
                    </Box>
                ) : requestProcessData ? (
                        <>
                            <Typography color="success" variant="h5" sx={{ mt: 2, textAlign: 'center' }}>
                                Movement to the reserve fund created successfully
                            </Typography>
                            <Typography variant="h6" sx={{ mt: 2, textAlign: 'center' }}>
                                You can check your movements in the "Balance Movements" option of the side menu.
                            </Typography>
                            <Button variant="outlined" color="primary" onClick={() => props.onClose()} sx={{ mt: 3 }}>
                                Close
                            </Button>
                        </>
                ) : (
                    <>
                        <TextField
                            label="Amount to move to the reserve"
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
                                disabled={!requestedAmount || requestingBalanceMovement}
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