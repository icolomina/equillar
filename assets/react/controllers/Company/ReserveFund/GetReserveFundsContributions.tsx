/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { useQuery } from "@tanstack/react-query";
import { useApi } from "../../../hooks/ApiHook";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import axios, { AxiosError, AxiosResponse } from "axios";
import { ContractReserveFund } from "../../../model/contract";
import { Fragment } from "react/jsx-runtime";
import { Backdrop, Box, Button, CircularProgress, Paper, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Typography } from "@mui/material";
import PageListWrapper from "../../Miscelanea/Wrapper/PageListWrapper";
import { useAuth } from "../../../hooks/AuthHook";
import { useState } from "react";
import CheckReserveFundContributionModal from "./CheckReserveFundContributionModal";

interface ContractReserveFundCheckResult {
    status: string
}

export default function GetReserveFundsContributions() {

    const { callGet } = useApi();
    const { isAdmin } = useAuth();
    const routes = useApiRoutes();

    const [selectedReserveFundContribution, setSelectedReserveFundContribution] = useState<ContractReserveFund>(null);
    const [openModalToCheckReserveFundContribution, setOpenModalToCheckReserveFundContribution] = useState<boolean>(false);

    const query = useQuery(
        {
            queryKey: ['get-reserve-fund-contributions'],
            queryFn: async () => {
                const result: AxiosResponse<ContractReserveFund[]> | AxiosError = await callGet<object, ContractReserveFund[]>(routes.getReserveFundContributions, {});
                if (!axios.isAxiosError(result)) {
                    return result.data;
                }

                throw new Error(result.message);
            },
            retry: 0,
        }
    );

    const closeCheckReserveFundContributionlModal = () => {
        setSelectedReserveFundContribution(null);
        setOpenModalToCheckReserveFundContribution(false);
        query.refetch();
    }

    const handleOpenCheckReserveFundContribution = (c: ContractReserveFund) => {
        setSelectedReserveFundContribution(c);
        setOpenModalToCheckReserveFundContribution(true);
    }

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
                            You have not yet contributed to the reserve fund. You can make a contribution request from the contract details page. This option will be 
                            available after the contract is activated.
                        </Typography>
                    </Box>
                </Fragment>
            );
        }
    }

    return (
        <Fragment>
            <PageListWrapper title="Reserve Fund contributions list">
                <TableContainer component={Paper}>
                    <Table sx={{ minWidth: 650 }} aria-label="simple table">
                        <TableHead>
                            <TableRow>
                                <TableCell>Contract name</TableCell>
                                <TableCell align="right">Amount</TableCell>
                                <TableCell align="right">Status</TableCell>
                                <TableCell align="right">Created at</TableCell>
                                <TableCell align="right">Received at</TableCell>
                                <TableCell align="right">Transferred at</TableCell>
                                <TableCell align="right">Options</TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {query.data.map((c: ContractReserveFund) => (
                                <TableRow
                                    key={c.id}
                                    sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                                >
                                    <TableCell component="th" scope="row">
                                        {c.contractLabel}
                                    </TableCell>
                                    <TableCell align="right">{c.amount}</TableCell>
                                    <TableCell align="right">{c.status}</TableCell>
                                    <TableCell align="right">{c.createdAt}</TableCell>
                                    <TableCell align="right">{c.receivedAt}</TableCell>
                                    <TableCell align="right">{c.transferredAt}</TableCell>
                                    <TableCell align="right">
                                        {isAdmin() && (c.status == 'CREATED' || c.status == 'INSUFFICIENT_FUNDS_RECEIVED' ) && <Button variant="contained" color="primary" size="small" sx={{ mr: 1 }} onClick={() => handleOpenCheckReserveFundContribution(c)}>
                                            Check
                                        </Button>}
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </TableContainer>
            </PageListWrapper>

            {selectedReserveFundContribution &&
                <CheckReserveFundContributionModal
                    open={openModalToCheckReserveFundContribution}
                    contractReserveFund={selectedReserveFundContribution}
                    onClose={closeCheckReserveFundContributionlModal}

                />}
        </Fragment>
    );

}