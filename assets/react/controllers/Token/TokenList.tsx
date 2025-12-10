// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Fragment } from 'react';
import { useQuery } from '@tanstack/react-query';
import { useApi } from '../../hooks/ApiHook';
import { useApiRoutes } from '../../hooks/ApiRoutesHook';
import { Backdrop, Button, CircularProgress, Paper, Table, TableBody, TableCell, TableContainer, TableHead, TableRow } from '@mui/material';
import axios, { AxiosError, AxiosResponse } from 'axios';
import PageListWrapper from '../Miscelanea/Wrapper/PageListWrapper';
import { Token } from '../../model/token';


export default function TokenList() {

    const { callGet } = useApi();
    const apiRoutes = useApiRoutes();

    const query = useQuery({
        queryKey: ['availableTokens'],
        queryFn: async () => {
            const result: AxiosResponse<Token[]>|AxiosError = await callGet<object, Token[]>(apiRoutes.getAvailableTokens, {});
            if(!axios.isAxiosError(result)) {
                return result.data;
            }

            throw new Error(result.message);
        },
        retry: 0
    })

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

        return (
            <PageListWrapper title="Tokens list" >
                <TableContainer component={Paper}>
                    <Table sx={{ minWidth: 650 }} aria-label="simple table">
                        <TableHead>
                            <TableRow>
                                <TableCell align="right">Nombre</TableCell>
                                <TableCell align="right">Acronimo</TableCell>
                                <TableCell align="right">Emisor</TableCell>
                                <TableCell align="right">Fiat reference</TableCell>
                                <TableCell align="right">Decimales</TableCell>
                                <TableCell align="right">Opciones</TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {query.data.map((t: Token) => (
                                <TableRow key={t.id} sx={{ '&:last-child td, &:last-child th': { border: 0 } }}>
                                    <TableCell align="right">{t.name}</TableCell>
                                    <TableCell align="right">{t.code}</TableCell>
                                    <TableCell align="right">{t.issuer}</TableCell>
                                    <TableCell align="right">{t.fiatReference}</TableCell>
                                    <TableCell align="right">{t.decimals}</TableCell>
                                    <TableCell align="right">
                                        <Button variant="contained" color="primary" size="small" sx={{ mr: 1 }} >
                                            Edit
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </TableContainer>
            </PageListWrapper>
        )
    }

}
