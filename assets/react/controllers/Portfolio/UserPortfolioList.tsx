// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Fragment } from "react/jsx-runtime";
import { UserContractInvestment } from "../../model/user";
import { Button, Grid2, Paper, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, useTheme } from "@mui/material";
import { useNavigate } from "react-router-dom";
import { formatCurrencyFromValueAndTokenContract } from "../../utils/currency";


export interface UserPortfolioListProps {
    ucl: UserContractInvestment[]
}

export default function UserPortfolioList({ucl}: UserPortfolioListProps) {

    const theme = useTheme();
    const navigate = useNavigate();

    const handleViewUserContract = (uc: UserContractInvestment) => {
        return navigate('/app/user-contract/' + uc.id + '/edit');
    } 

    return (
        <Fragment>
            <Grid2 container spacing={2} sx={{ width: '100%' }}>
                <Grid2 size={12}>
                    <TableContainer component={Paper} sx={{ width: '100%', boxShadow: 1 }}>
                        <Table sx={{ width: '100%' }} aria-label="simple table">
                            <TableHead sx={{
                                backgroundColor: theme.palette.grey[100],
                                '& th': {
                                    fontWeight: 600,
                                    color: theme.palette.text.secondary,
                                },
                            }}>
                                <TableRow>
                                    <TableCell>Project name</TableCell>
                                    <TableCell align="right">Issuer</TableCell>
                                    <TableCell align="right">Token</TableCell>
                                    <TableCell align="right">Rate</TableCell>
                                    <TableCell align="right">Deposited</TableCell>
                                    <TableCell align="right">Interests</TableCell>
                                    <TableCell align="right">Commission</TableCell>
                                    <TableCell align="right">Total to receive</TableCell>
                                    <TableCell align="right">Status</TableCell>
                                    <TableCell align="right">First payment date</TableCell>
                                    <TableCell align="right">Options</TableCell>
                                </TableRow>
                            </TableHead>
                            <TableBody>
                                {ucl.map((uc: UserContractInvestment) => (
                                    <TableRow
                                        key={uc.contractLabel}
                                        sx={{
                                            '&:last-child td, &:last-child th': { borderBottom: 0 },
                                            '& td': { padding: theme.spacing(1.5) },
                                        }}
                                    >
                                        <TableCell component="th" scope="row">{uc.contractLabel}</TableCell>
                                        <TableCell component="th" scope="row">{uc.contractIssuer}</TableCell>
                                        <TableCell align="right">{uc.tokenContract.name} - {uc.tokenContract.code}</TableCell>
                                        <TableCell align="right">{uc.rate}%</TableCell>
                                        <TableCell align="right">{formatCurrencyFromValueAndTokenContract(uc.deposited, uc.tokenContract)}</TableCell>
                                        <TableCell align="right">{formatCurrencyFromValueAndTokenContract(uc.interest, uc.tokenContract)}</TableCell>
                                        <TableCell align="right">{formatCurrencyFromValueAndTokenContract(uc.commission, uc.tokenContract)}</TableCell>
                                        <TableCell align="right">{formatCurrencyFromValueAndTokenContract(uc.total, uc.tokenContract)}</TableCell>
                                        <TableCell align="right">{uc.status}</TableCell>
                                        <TableCell align="right">{uc.withdrawalDate}</TableCell>
                                        <TableCell align="right">
                                            <Button variant="contained" color="primary" size="small" sx={{ mr: 1 }} onClick={() => handleViewUserContract(uc)}  >
                                                More info
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </TableContainer>
                </Grid2>
            </Grid2>
        </Fragment>
    )
}