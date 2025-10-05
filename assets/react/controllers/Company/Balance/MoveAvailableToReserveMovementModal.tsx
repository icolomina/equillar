/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { useApi } from "../../../hooks/ApiHook";
import { ContractBalanceMovement } from "../../../model/contract";
import { useState } from "react";
import { Box, Button, CircularProgress, Modal, TextField, Typography, useMediaQuery, useTheme } from "@mui/material";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import { AxiosResponse } from "axios";

interface MoveAvailableToReserveMovementProps {
    open: boolean;
    contractBalanceMovement: ContractBalanceMovement;
    onClose: () => void;
}

export default function MoveAvailableToReserveMovementModal(props: MoveAvailableToReserveMovementProps) {

    const { callPatch } = useApi();
    const apiRoutes = useApiRoutes();
    const theme = useTheme();

    const [requestProcessData, setRequestProcessData] = useState<{status: string}>(null);
    const [movingBalance, setMovingBalanceMovement] = useState<boolean>(false);

    const isMobile = useMediaQuery(theme.breakpoints.down('sm'));

    const handleSubmit = () => {
        setMovingBalanceMovement(true);
        callPatch<object, {status: string}>(apiRoutes.moveAvailableToReserveFundMovement(props.contractBalanceMovement.id), {}).then(
            (result: AxiosResponse<{status: string}>) => {
                setRequestProcessData(result.data);
                setMovingBalanceMovement(false);
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
                    Perform movement to the reserve fund
                </Typography>

                {movingBalance ? (
                    <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', mt: 2 }}>
                        <CircularProgress />
                        <Typography sx={{ mt: 1 }}>Processing. Please wait a moment...</Typography>
                    </Box>
                ) : requestProcessData ? (
                        <>
                            <Typography color="success" variant="h5" sx={{ mt: 2, textAlign: 'center' }}>
                                Movement to the reserve fund performed successfully
                            </Typography>
                            <Button variant="outlined" color="primary" onClick={() => props.onClose()} sx={{ mt: 3 }}>
                                Close
                            </Button>
                        </>
                ) : (
                    <>
                        <Typography variant="h5" sx={{ mt: 2, textAlign: 'center' }}>
                            The transfer to the reserve fund will be made. Are you sure?
                        </Typography>
                        
                        <Box sx={{ display: 'flex', justifyContent: 'center', width: '100%', mt: 3 }}>
                            <Button onClick={() => props.onClose()} sx={{ mr: 1 }}>
                                Cancel
                            </Button>
                            <Button
                                variant="contained"
                                color="primary"
                                onClick={handleSubmit}
                                disabled={movingBalance}
                            >
                                Ok. Go on
                            </Button>
                        </Box>
                    </>
                )}
            </Box>
        </Modal>
    );
}