// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import axios, { AxiosError, AxiosResponse } from "axios";
import { useApi } from "../../../hooks/ApiHook";
import { ContractReserveFund } from "../../../model/contract";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import { Fragment, useEffect, useState } from "react";
import { Box, Button, CircularProgress, Modal, Typography } from "@mui/material";
import { StyledModalBox } from "../../Theme/Styled/Modal";

interface CheckReserveFundContributionModalProps {
    open: boolean;
    contractReserveFund: ContractReserveFund;
    onClose: () => void;
}

export default function CheckReserveFundContributionModal(props: CheckReserveFundContributionModalProps) {

    const { callPatch } = useApi();
    const routes = useApiRoutes();
    const [reserveFundContributionStatus, setReserveFundContributionStatus] = useState<string>(null);
    const [loading, setLoading] = useState<boolean>(false);
    const [sendingToReserveFund, setSendingToReserveFund] = useState<boolean>(false);


    useEffect(() => {
        if (props.open) {
            setLoading(true);
            callPatch<object, { status: string }>(routes.checkReserveFundContribution(props.contractReserveFund.id),{}).then(
                (result: AxiosResponse<{status: string}>|AxiosError) => {
                    (axios.isAxiosError(result)) 
                        ? setReserveFundContributionStatus('FAILED')
                        : setReserveFundContributionStatus(result.data.status)
                    ;

                    setLoading(false);
                }
            )
            
        }
    }, []);

    const handleTransferToReserveFund = async () => {
        setSendingToReserveFund(true);
        callPatch<object, {status: string}>(routes.transferReserveFundContributon(props.contractReserveFund.id), {}).then(
            (result: AxiosResponse<{status: string}>|AxiosError) => {
                (axios.isAxiosError(result)) 
                    ? setReserveFundContributionStatus('FAILED')
                    : setReserveFundContributionStatus(result.data.status)
                ;
                
                setSendingToReserveFund(false);
            }
        )
    }

    return (
        <Modal open={props.open} >
            <StyledModalBox width={600} useFlex={true}>
                {loading || sendingToReserveFund ? (
                    <Fragment>
                        { loading ? ( <Typography sx={{ mb: 1, textAlign: 'center' }}>
                                Checking Reserve Fund contribution
                            </Typography>
                        ) : (
                            <Typography sx={{ mb: 1, textAlign: 'center' }}>
                                Transferring contribution to the contract reserve fund
                            </Typography>
                        )}

                        <CircularProgress />
                    </Fragment>
                ) : (
                    <Fragment>
                        {reserveFundContributionStatus === 'CREATED' && (
                            <>
                                <Typography sx={{ mb: 1, textAlign: 'center'}}>
                                    The contribution payment has not been received yet
                                </Typography>
                                <Box sx={{ display: 'flex', justifyContent: 'center', width: '100%' }}>
                                    <Button onClick={() => props.onClose()} sx={{ mr: 1 }}>
                                        Ok
                                    </Button>
                                </Box>
                            </>
                        )}
                        {reserveFundContributionStatus === 'FAILED' && (
                            <Typography sx={{ mb: 1, textAlign: 'center'}}>
                                An error ocurred during the contribution checking. Please, try again later ...
                            </Typography>
                        )}
                        {reserveFundContributionStatus === 'RECEIVED' && (
                            <Fragment>
                                <Typography sx={{ mb: 1, textAlign: 'center'}}>The contribution has been received successfully</Typography>
                                <Typography sx={{ mb: 1, textAlign: 'center'}}>Do you want to tranfer the funds to the contract reserve fund ?</Typography>
                                <Box sx={{ display: 'flex', justifyContent: 'center', width: '100%' }}>
                                    <Button onClick={() => props.onClose()} sx={{ mr: 1 }}>
                                        Cancel
                                    </Button>
                                    <Button
                                        variant="contained"
                                        color="primary"
                                        onClick={handleTransferToReserveFund}
                                    >
                                        Transfer to reserve fund
                                    </Button>
                                </Box>
                            </Fragment>
                            
                        )}
                        {reserveFundContributionStatus === 'TRANSFERRED' && (
                            <Fragment>
                                <Typography sx={{ mb: 1, textAlign: 'center'}}>The contribution has been trabnsferred to the reserve fund successfully</Typography>
                                <Box sx={{ display: 'flex', justifyContent: 'flex-end', width: '100%' }}>
                                    <Button onClick={() => props.onClose()} sx={{ mr: 1 }}>
                                        Cancel
                                    </Button>                                   
                                </Box>
                            </Fragment>
                            
                        )}
                    </Fragment>
                )}
            </StyledModalBox>
        </Modal>
    );
  
}