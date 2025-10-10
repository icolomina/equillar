/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { useQuery } from "@tanstack/react-query";
import { useApi } from "../../../hooks/ApiHook";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import axios, { AxiosError, AxiosResponse } from "axios";
import { ContractBalanceMovement, ContractReserveFund } from "../../../model/contract";
import { Fragment } from "react/jsx-runtime";
import { Backdrop, Box, Button, CircularProgress, Paper, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Typography } from "@mui/material";
import PageListWrapper from "../../Miscelanea/Wrapper/PageListWrapper";
import { useAuth } from "../../../hooks/AuthHook";
import { useState } from "react";
import MoveAvailableToReserveMovementModal from "./MoveAvailableToReserveMovementModal";
import EditStellarTransactionDataModal from "../../Blockchain/EditStellarTransactionDataModal";

export default function GetContractBalanceMovements() {

    const { callGet } = useApi();
    const { isAdmin } = useAuth();
    const routes = useApiRoutes();

    const [selectedContractBalanceMovement, setSelectedContractBalanceMovement] = useState<ContractBalanceMovement>(null);
    const [openModalToPerformBalanceMovement, setOpenModalToPerformBalanceMovement] = useState<boolean>(false);
    const [selectedContractBalanceMovementForTrxInfo, setSelectedContractBalanceMovementForTrxInfo] = useState<ContractBalanceMovement>(null);
    const [openTrxInfoModal, setOpenTrxInfoModal] = useState<boolean>(false);

    const query = useQuery(
        {
            queryKey: ['get-cntract-balance-movements'],
            queryFn: async () => {
                const result: AxiosResponse<ContractBalanceMovement[]> | AxiosError = await callGet<object, ContractBalanceMovement[]>(routes.getContractBalanceMovements, {});
                if (!axios.isAxiosError(result)) {
                    return result.data;
                }

                throw new Error(result.message);
            },
            retry: 0,
        }
    );

    const closePerformBalanceMovementlModal = () => {
        setSelectedContractBalanceMovement(null);
        setOpenModalToPerformBalanceMovement(false);
        query.refetch();
    }

    const handleOpenPerformBalanceMovementlModal = (c: ContractBalanceMovement) => {
        setSelectedContractBalanceMovement(c);
        setOpenModalToPerformBalanceMovement(true);
    }

    const handleOpenTrxInfoModal = (c: ContractBalanceMovement) => {
        setSelectedContractBalanceMovementForTrxInfo(c);
        setOpenTrxInfoModal(true);
    };
        
    const handleCloseTrxInfoModal = () => {
        setSelectedContractBalanceMovementForTrxInfo(null);
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
                            You have not created any balance movement so far. You can request one from the contract details page. This option will be 
                            available after the contract is activated and has available balance.
                        </Typography>
                    </Box>
                </Fragment>
            );
        }
    }

    return (
        <Fragment>
            <PageListWrapper title="Contract balance movements list">
                <TableContainer component={Paper}>
                    <Table sx={{ minWidth: 650 }} aria-label="simple table">
                        <TableHead>
                            <TableRow>
                                <TableCell>Contract name</TableCell>
                                <TableCell align="right">Amount</TableCell>
                                <TableCell align="right">From segment</TableCell>
                                <TableCell align="right">To segment</TableCell>
                                <TableCell align="right">Status</TableCell>
                                <TableCell align="right">Created at</TableCell>
                                <TableCell align="right">Moved at</TableCell>
                                <TableCell align="right">Options</TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {query.data.map((c: ContractBalanceMovement) => (
                                <TableRow
                                    key={c.id}
                                    sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                                >
                                    <TableCell component="th" scope="row">
                                        {c.contractName}
                                    </TableCell>
                                    <TableCell align="right">{c.amount}</TableCell>
                                    <TableCell align="right">{c.segmentFrom}</TableCell>
                                    <TableCell align="right">{c.segmentTo}</TableCell>
                                    <TableCell align="right">{c.status}</TableCell>
                                    <TableCell align="right">{c.createdAt}</TableCell>
                                    <TableCell align="right">{c.movedAt}</TableCell>
                                    <TableCell align="right">
                                        {isAdmin() && (c.status == 'CREATED' ) && <Button variant="contained" color="primary" size="small" sx={{ mr: 1 }} onClick={() => handleOpenPerformBalanceMovementlModal(c)}>
                                            Move to the reserve
                                        </Button>}
                                        {c.movedAt && (
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

            {selectedContractBalanceMovement &&
                <MoveAvailableToReserveMovementModal
                    open={openModalToPerformBalanceMovement}
                    contractBalanceMovement={selectedContractBalanceMovement}
                    onClose={closePerformBalanceMovementlModal}
            />}

            {openTrxInfoModal && selectedContractBalanceMovementForTrxInfo && (
                <EditStellarTransactionDataModal
                    openModal={openTrxInfoModal}
                    hash={selectedContractBalanceMovementForTrxInfo.hash}
                    handleCloseModal={handleCloseTrxInfoModal}
                />
            )}
            
        </Fragment>
    );

}