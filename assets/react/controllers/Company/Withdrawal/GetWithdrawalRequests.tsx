// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { useQuery } from "@tanstack/react-query";
import axios, { AxiosError, AxiosResponse } from "axios";
import { ContractWithdrawal } from "../../../model/contract";
import { useApi } from "../../../hooks/ApiHook";
import { Fragment } from "react/jsx-runtime";
import { Backdrop, Box, Button, CircularProgress, Paper, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Typography } from "@mui/material";
import { useState } from "react";
import PageListWrapper from "../../Miscelanea/Wrapper/PageListWrapper";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import { useAuth } from "../../../hooks/AuthHook";
import ApproveWithdrawalRequestModal from "./ApproveWithdrawalRequestModal";
import EditStellarTransactionDataModal from "../../Blockchain/EditStellarTransactionDataModal";

export default function GetWithdrawalRequests() {

    const { callGet } = useApi();
    const {isAdmin} = useAuth();
    const routes = useApiRoutes();
    const [selectedRequestWithdrawalToApprove, setSelectedRequestWithdrawalToApprove] = useState<ContractWithdrawal>(null);
    const [openModalToApproveWithdrawal, setOpenModalToApproveWithdrawal] = useState<boolean>(false);
    const [selectedWithdrawalForTrxInfo, setSelectedWithdrawalForTrxInfo] = useState<ContractWithdrawal>(null);
    const [openTrxInfoModal, setOpenTrxInfoModal] = useState<boolean>(false);

    const query = useQuery(
        {
            queryKey: ['get-withdrawal-requests'],
            queryFn: async () => {
                const result: AxiosResponse<ContractWithdrawal[]> | AxiosError = await callGet<object, ContractWithdrawal[]>(routes.getWithdrawalRequests, {});
                if (!axios.isAxiosError(result)) {
                    return result.data;
                }

                throw new Error(result.message);
            },
            retry: 0,
        }
    );

    const handleOpenApproveWithdrawalModal = (c: ContractWithdrawal) => {
        setSelectedRequestWithdrawalToApprove(c);
        setOpenModalToApproveWithdrawal(true);
    }

    const closeApproveWithdrawalModal = () => {
        setSelectedRequestWithdrawalToApprove(null);
        setOpenModalToApproveWithdrawal(false);
    }

    const handleReloadAfterSuccessfulApproval = () => {
        query.refetch();
    }

    const handleRejectWithdrawalRequest = () => {

    }

    const handleOpenTrxInfoModal = (c: ContractWithdrawal) => {
        setSelectedWithdrawalForTrxInfo(c);
        setOpenTrxInfoModal(true);
    };

    const handleCloseTrxInfoModal = () => {
        setSelectedWithdrawalForTrxInfo(null);
        setOpenTrxInfoModal(false);
    };

    if (query.isLoading) {
        return (
            <Fragment>
                <Backdrop
                    sx={(theme) => ({ color: '#fff', zIndex: theme.zIndex.drawer + 1 })}
                    open={query.isLoading}
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
                            You have not yet requested a withdrawal. You can request a withdrawal from your project list. 
                            Projects that have already received funding will have a "Request Withdrawal" button that allows you to submit your request.
                        </Typography>
                    </Box>
                </Fragment>
            );
        }
    }

    return (
        <Fragment>
            <PageListWrapper title="Withdrawal requests list">
                <TableContainer component={Paper}>
                    <Table sx={{ minWidth: 650 }} aria-label="simple table">
                        <TableHead>
                            <TableRow>
                                <TableCell>Contract name</TableCell>
                                <TableCell align="right">Requested By</TableCell>
                                <TableCell align="right">Requested At</TableCell>
                                <TableCell align="right">Requested Amount</TableCell>
                                <TableCell align="right">Status</TableCell>
                                <TableCell align="right">Approved at</TableCell>
                                <TableCell align="right">Options</TableCell>

                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {query.data.map((c: ContractWithdrawal) => (
                                <TableRow
                                    key={c.id}
                                    sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                                >
                                    <TableCell component="th" scope="row">
                                        {c.contractLabel}
                                    </TableCell>
                                    <TableCell align="right">{c.requestedBy}</TableCell>
                                    <TableCell align="right">{c.requestedAt}</TableCell>
                                    <TableCell align="right">{c.requestedAmount}</TableCell>
                                    <TableCell align="right">{c.status}</TableCell>
                                    <TableCell align="right">{c.approvedAt}</TableCell>
                                    <TableCell align="right">
                                        {isAdmin() && c.status == 'REQUESTED' && <Button variant="contained" color="success" size="small" sx={{ mr: 1 }} onClick={() => handleOpenApproveWithdrawalModal(c)}>
                                            Approve
                                        </Button>}
                                        {isAdmin() && !c.approvedAt && <Button variant="contained" color="warning" size="small" sx={{ mr: 1 }} onClick={handleRejectWithdrawalRequest}>
                                            Reject
                                        </Button>}
                                        {c.approvedAt && (
                                            <Button
                                                variant="contained"
                                                color="primary"
                                                size="small"
                                                sx={{ mr: 1 }}
                                                onClick={() => handleOpenTrxInfoModal(c)}
                                                disabled={!c.hash}
                                            >
                                                Trx info
                                            </Button>
                                        )}
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </TableContainer>
            </PageListWrapper>

            { selectedRequestWithdrawalToApprove && 
                <ApproveWithdrawalRequestModal
                    open={openModalToApproveWithdrawal}
                    contractWithdrawal={selectedRequestWithdrawalToApprove}
                    onClose={closeApproveWithdrawalModal}
                    handleApprovalFinished={handleReloadAfterSuccessfulApproval}
                  
              /> }

            {openTrxInfoModal && selectedWithdrawalForTrxInfo && (
                <EditStellarTransactionDataModal
                    openModal={openTrxInfoModal}
                    hash={selectedWithdrawalForTrxInfo.hash}
                    handleCloseModal={handleCloseTrxInfoModal}
                />
            )}
        </Fragment>
    );
}