/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { useApi } from "../../../hooks/ApiHook";
import { ContractWithdrawal } from "../../../model/contract";
import { Fragment, useState } from "react";
import { Box, Button, CircularProgress, Modal, Typography, useMediaQuery, useTheme } from "@mui/material";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import axios, { AxiosError, AxiosResponse } from "axios";

interface ApproveWithdrawalModalProps {
    open: boolean;
    contractWithdrawal: ContractWithdrawal;
    onClose: () => void;
    handleApprovalFinished: () => void;
}

export default function ApproveWithdrawalRequestModal(props: ApproveWithdrawalModalProps) {

    const { callPatch } = useApi();
    const theme = useTheme();
    const routes = useApiRoutes();

    const [error, setError] = useState<string>('');
    const [successMessage, setSuccessMessage] = useState<string>('');
    const [approvingWithdrawal, setApprovingWithdrawal] = useState<boolean>(false);

    const isMobile = useMediaQuery(theme.breakpoints.down('sm'));

    const handleApproveWithdrawalRequest = () => {
        setApprovingWithdrawal(true);
        callPatch(routes.approveWithdrawal(props.contractWithdrawal.id), {}).then(
            (r: AxiosResponse | AxiosError) => {
                (axios.isAxiosError(r)) 
                    ? setError('And error ocurred while tranferring the funds. Plase try it later')
                    : setSuccessMessage('Funds transferred successfully')
                ;

                setApprovingWithdrawal(false);
            }
        ).catch(() => {
            setApprovingWithdrawal(false);
            handleCloseOnSuccess();
        })
    }


    const handleCloseOnSuccess = () => {
        props.handleApprovalFinished();
        props.onClose();
    }

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
                    width: isMobile ? '90%' : 400, 
                    maxWidth: 600, 
                    display: 'flex',
                    flexDirection: 'column',
                    alignItems: 'center',
                }}
            >
                <Typography id="modal-modal-title" variant="h5" component="h2" mb={2} textAlign="center">
                    Approve Withdrawal Request
                </Typography>
                {successMessage.length === 0 && !approvingWithdrawal && error.length === 0 &&

                    <Fragment>
                        <Typography id="modal-modal-description-1" sx={{ mb: 2, textAlign: 'center' }} >
                            <Typography component="span" variant="subtitle1" fontWeight="bold">
                                Requested funds to approve:
                            </Typography>&nbsp;
                            <Typography component="span" variant="subtitle1" color="primary" fontWeight="bold">
                                {props.contractWithdrawal.requestedAmount}
                            </Typography>
                            <Typography component="span" display="block" variant="subtitle1" sx={{ mt: 1 }}  >
                                After approving this withdrawal request, the funds will be sent to the company address.
                            </Typography>
                        </Typography>

                        <Box sx={{ display: 'flex', justifyContent: 'center', width: '100%' }}>
                            <Button onClick={() => props.onClose()} sx={{ mr: 1 }}>
                                Cancel
                            </Button>
                            <Button
                                variant="contained"
                                color="primary"
                                onClick={handleApproveWithdrawalRequest}
                                disabled={approvingWithdrawal}
                            >
                                Approve
                            </Button>
                        </Box>
                    </Fragment>
                }

                {approvingWithdrawal ? (
                    <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', mt: 2 }}>
                        <CircularProgress />
                        <Typography sx={{ mt: 1 }}>Transferring requested amount. Please wait a moment...</Typography>
                    </Box>
                ) : successMessage ? (
                    <>
                        <Typography color="success" sx={{ mt: 2, textAlign: 'center' }}>
                            {successMessage}
                        </Typography>
                        <Button variant="outlined" color="primary" onClick={() => handleCloseOnSuccess()}>
                            Close
                        </Button>
                    </>
                ) : error &&
                    <>
                        <Typography color="warning" sx={{ mt: 2, textAlign: 'center' }}>
                            {error}
                        </Typography>
                        <Button variant="outlined" color="primary" onClick={() => props.onClose()}>
                            Close
                        </Button>
                    </>
                }
            </Box>
        </Modal>
    );
}