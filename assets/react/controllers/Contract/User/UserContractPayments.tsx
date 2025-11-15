// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { useQuery } from "@tanstack/react-query";
import { useApi } from "../../../hooks/ApiHook";
import axios, { AxiosError, AxiosResponse } from "axios";
import { UserContractPayment } from "../../../model/user";
import { Fragment } from "react/jsx-runtime";
import { Backdrop, Box, Button, CircularProgress, Paper, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Typography } from "@mui/material";
import { useState } from "react";
import PageListWrapper from "../../Miscelanea/Wrapper/PageListWrapper";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import EditStellarTransactionDataModal from "../../Blockchain/EditStellarTransactionDataModal";

export default function UserContractPayments() {

    const { callGet } = useApi();
    const routes = useApiRoutes();
    const [selectedUcPayment, setSelectedUcPayment] = useState<UserContractPayment>(null);
    
    const [openTrxInfoModal, setOpenTrxInfoModal] = useState<boolean>(false);

    const query = useQuery({
        queryKey: ['userContractPayments'],
        queryFn: async () => {
            const result: AxiosResponse<UserContractPayment[]> | AxiosError = await callGet<object, UserContractPayment[]>(routes.getUserPayments, {});
            if (!axios.isAxiosError(result)) {
                return result.data;
            }

            throw new Error(result.message);
        },
        retry: 0
    });

    const handleViewTrxInfo = (ucp: UserContractPayment) => {
        setSelectedUcPayment(ucp);
        setOpenTrxInfoModal(true);
    }

    const handleCloseTrxInfoModal = () => {
        setSelectedUcPayment(null);
        setOpenTrxInfoModal(false);
    }   

    if (query.isLoading) {
        return (
            <Fragment>
                <Backdrop
                    sx={(theme) => ({ color: '#fff', zIndex: theme.zIndex.drawer + 1 })}
                    open={query.isLoading}
                //onClick={handleClose}
                >
                    <CircularProgress color="inherit" />
                </Backdrop>
            </Fragment>
        );
    }
    else {
        if (query.isFetched && query.data?.length === 0) {
            return (
                <Fragment>
                    <Box sx={{ p: 2, mt: 2, display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center', height: '100%' }} >
                        <Typography variant="h6" sx={{ mb: 2 }}>
                            You have no received any payment yet
                        </Typography>
                    </Box>

                </Fragment>
            );
        }
    }

    return (
        <Fragment>
            <PageListWrapper title="Last payments list" sx={{}} >
                <TableContainer component={Paper}>
                    <Table sx={{ minWidth: 650 }} aria-label="simple table">
                        <TableHead>
                            <TableRow>
                                <TableCell>Issuer name</TableCell>
                                <TableCell>Project name</TableCell>
                                <TableCell align="right">Payment emitted At</TableCell>
                                <TableCell align="right">Status</TableCell>
                                <TableCell align="right">Total to receive</TableCell>
                                <TableCell align="right">Total received</TableCell>
                                <TableCell align="right">Payment received At</TableCell>
                                <TableCell align="right">Options</TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {query.data.map((ucp: UserContractPayment) => (
                                <TableRow key={ucp.id} sx={{ '&:last-child td, &:last-child th': { border: 0 } }}>
                                    <TableCell component="th" scope="row">{ucp.projectIssuer}</TableCell>
                                    <TableCell component="th" scope="row">{ucp.projectName}</TableCell>
                                    <TableCell align="right">{ucp.paymentEmittedAt}</TableCell>
                                    <TableCell align="right">{ucp.status}</TableCell>
                                    <TableCell align="right">{ucp.totalToReceive}</TableCell>
                                    <TableCell align="right">{ucp.totalReceived}</TableCell>
                                    <TableCell align="right">{ucp.paymentPaidAt}</TableCell>
                                    <TableCell align="right">
                                        <Button disabled={!ucp.hash} variant="contained" color="primary" size="small" sx={{ mr: 1 }} onClick={() => handleViewTrxInfo(ucp)} >
                                            Trx info
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </TableContainer>
            </PageListWrapper>
            
            { openTrxInfoModal && selectedUcPayment && <EditStellarTransactionDataModal
                openModal={openTrxInfoModal} hash={selectedUcPayment.hash} handleCloseModal={handleCloseTrxInfoModal}>
                </EditStellarTransactionDataModal>}
        </Fragment>
    )
}