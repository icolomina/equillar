/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { useQuery } from "@tanstack/react-query";
import { useApi } from "../../../hooks/ApiHook";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import { useParams } from "react-router-dom";
import axios, { AxiosError, AxiosResponse } from "axios";
import { UserContractInvestment } from "../../../model/user";
import { Fragment } from "react/jsx-runtime";
import { Backdrop, Box, Card, CardContent, CircularProgress, Divider, Grid2, Typography } from "@mui/material";
import { formatCurrencyFromValueAndTokenContract } from "../../../utils/currency";

import UserContractPaymentsCalendar from "./Payments/UserContractPaymentsCalendar";

export default function EditUserContract() {
    
    const params = useParams();
    const { callGet } = useApi();
    const apiRoutes = useApiRoutes();

    const query = useQuery<UserContractInvestment>({
        queryKey: ['userContractEdit', params.id],
        queryFn: async () => {
            const result: AxiosResponse<UserContractInvestment> | AxiosError = await callGet<object, UserContractInvestment>(apiRoutes.editUserContract(params.id), {});
            if (!axios.isAxiosError(result)) {
                return result.data;
            }

            throw new Error(result.message);
        },
        retry: 0
    });

    if(query.isLoading) {
        return (
            <Fragment>
                <Backdrop
                    sx={(theme) => ({ color: "#fff", zIndex: theme.zIndex.drawer + 1 })}
                    open={query.isLoading}
                //onClick={handleClose}
                >
                    <CircularProgress color="inherit" />
                </Backdrop>
            </Fragment>
        );
    }
    else{
        return (
            <Fragment>
                <Box sx={{ flexGrow: 1, p: 4 }}>
                    <Typography variant="h4" gutterBottom sx={{ mb: 2 }}>
                        User contract details: {query.data.contractLabel}
                    </Typography>
                    <Divider sx={{ mb: 4 }} />
                    <Grid2 container spacing={4}>

                        {/* Contenido principal del contrato */}
                        <Grid2 size={{ xs: 12, md: 8 }}>

                            {/* Tarjeta de información general */}
                            <Card sx={{ mb: 4, borderRadius: 2, boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)' }}>
                                <CardContent>
                                    <Typography variant="h6" gutterBottom sx={{ fontWeight: 'bold', mb: 2 }}>
                                        General Information
                                    </Typography>
                                    <Grid2 container spacing={3}>

                                        {/* Resto de la información general */}
                                        <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                            <Typography variant="subtitle2" color="textSecondary">Project</Typography>
                                            <Typography variant="body1" fontWeight="medium">{query.data.contractLabel} - ({query.data.contractIssuer})</Typography>
                                        </Grid2>
                                        <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                            <Typography variant="subtitle2" color="textSecondary">Token</Typography>
                                            <Typography variant="body1" fontWeight="medium">{query.data.tokenContract.name} - {query.data.tokenContract.code}</Typography>
                                        </Grid2>
                                        <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                            <Typography variant="subtitle2" color="textSecondary">Status</Typography>
                                            <Typography variant="body1" fontWeight="medium">{query.data.status}</Typography>
                                        </Grid2>
                                        <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                            <Typography variant="subtitle2" color="textSecondary">Starting payments date</Typography>
                                            <Typography variant="body1" fontWeight="medium">{query.data.withdrawalDate}</Typography>
                                        </Grid2>
                                        <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                            <Typography variant="subtitle2" color="textSecondary">Payment Type</Typography>
                                            <Typography variant="body1" fontWeight="medium">{query.data.paymentType}</Typography>
                                        </Grid2>
                                    </Grid2>
                                </CardContent>
                            </Card>

                            {/* Tarjeta de detalles financieros */}
                            <Card sx={{ mb: 4, borderRadius: 2, boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)' }}>
                                <CardContent>
                                    <Typography variant="h6" gutterBottom sx={{ fontWeight: 'bold', mb: 2 }}>
                                        Financial Details
                                    </Typography>
                                    <Grid2 container spacing={3}>
                                        {/* Información de fondos */}
                                        <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                            <Typography variant="subtitle2" color="textSecondary">Deposited</Typography>
                                            <Typography variant="body1" fontWeight="medium">
                                                {formatCurrencyFromValueAndTokenContract(query.data.deposited, query.data.tokenContract)}
                                            </Typography>
                                        </Grid2>
                                        <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                            <Typography variant="subtitle2" color="textSecondary">Interests</Typography>
                                            <Typography variant="body1" fontWeight="medium">
                                                {formatCurrencyFromValueAndTokenContract(query.data.interest, query.data.tokenContract)}
                                            </Typography>
                                        </Grid2>
                                        <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                            <Typography variant="subtitle2" color="textSecondary">Comissions</Typography>
                                            <Typography variant="body1" fontWeight="medium">
                                                {formatCurrencyFromValueAndTokenContract(query.data.commission, query.data.tokenContract)}
                                            </Typography>
                                        </Grid2>
                                        <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                            <Typography variant="subtitle2" color="textSecondary">Total to receive</Typography>
                                            <Typography variant="body1" fontWeight="medium">
                                                {formatCurrencyFromValueAndTokenContract(query.data.total, query.data.tokenContract)}
                                            </Typography>
                                        </Grid2>
                                        <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                            <Typography variant="subtitle2" color="textSecondary">Rate</Typography>
                                            <Typography variant="body1" fontWeight="medium">
                                                {query.data.rate}%
                                            </Typography>
                                        </Grid2>
                                    </Grid2>
                                </CardContent>
                            </Card>
                        </Grid2>
                        <Grid2 size={{ xs: 12, md: 4 }}>
                            <UserContractPaymentsCalendar paymentsCalendar={query.data.paymentsCalendar} tokenContract={query.data.tokenContract} ></UserContractPaymentsCalendar>
                        </Grid2>
                    </Grid2>
                </Box>

            </Fragment>
        )
    }
}