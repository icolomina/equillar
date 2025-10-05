/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { Fragment, useState } from "react";
import { useApi } from "../../hooks/ApiHook";
import { GetContractsPath } from "../../services/Api/Investment/ApiRoutes";
import axios, { AxiosError, AxiosResponse } from "axios";
import { Backdrop, Box, Button, CircularProgress, IconButton, Menu, MenuItem, Paper, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Typography} from "@mui/material";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../hooks/AuthHook";
import { useQuery } from "@tanstack/react-query";
import { ContractOutput } from "../../model/contract";
import { MoreVert } from "@mui/icons-material";
import ApproveContractModal from "../Contract/ApproveContractModal";
import PageListWrapper from "../Miscelanea/Wrapper/PageListWrapper";
import { getStatusColor } from "../Miscelanea/Utils/ContractStatus";
import { formatCurrencyFromValueAndTokenContract } from "../../utils/currency";

export default function HomeAdmin() {

  const { callGet } = useApi();
  const navigate = useNavigate();

  /**
   * Loading current company projects using useQuery hook
   */
  const query = useQuery(
    {
      queryKey: ['get-projects'],
      queryFn: async () => {
        const result: AxiosResponse<ContractOutput[]> | AxiosError = await callGet<object, ContractOutput[]>(GetContractsPath, {});
        if (!axios.isAxiosError(result)) {
          return result.data;
        }

        throw new Error(result.message);
      },
      retry: 0
    }
  );

  const handleView = (c: ContractOutput) => {
    return navigate('/app/project/' + c.id + '/view');
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
              There are no created projects so far
            </Typography>
          </Box>
        </Fragment>
      );
    }
  }

  return (

    <Fragment>
      <PageListWrapper title="Projects list" >
        <TableContainer component={Paper}>
          <Table sx={{ minWidth: 650 }} aria-label="simple table">
            <TableHead>
              <TableRow>
                <TableCell>Project name</TableCell>
                <TableCell align="right">Token</TableCell>
                <TableCell align="right">Rate</TableCell>
                <TableCell align="right">Claim Months</TableCell>
                <TableCell align="right">Investor payment type</TableCell>
                <TableCell align="right">Collected funds</TableCell>
                <TableCell align="right">Available funds to claim</TableCell>
                <TableCell align="right">Goal</TableCell>
                <TableCell align="right">Status</TableCell>
                <TableCell align="right">Options</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {query.data.map((c: ContractOutput) => (
                <TableRow
                  key={c.label}
                  sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                >
                  <TableCell component="th" scope="row">
                    {c.label}
                  </TableCell>
                  <TableCell align="right">{c.tokenContract.name} ({c.tokenContract.code})</TableCell>
                  <TableCell align="right">{c.rate}</TableCell>
                  <TableCell align="right">{c.claimMonths}</TableCell>
                  <TableCell align="right">{c.returnType}</TableCell>
                  <TableCell align="right">{c.contractBalance.available}</TableCell>
                  <TableCell align="right">{formatCurrencyFromValueAndTokenContract(c.contractBalance.available, c.tokenContract)}</TableCell>
                  <TableCell align="right">{c.goal}</TableCell>
                  <TableCell align="right">
                    <Typography sx={{ color: getStatusColor(c.status) }}>
                      {c.status}
                    </Typography>
                  </TableCell>
                  <TableCell align="right">
                    <Button variant="contained" color="primary" size="small" sx={{ mr: 1 }} onClick={() => handleView(c)} >
                        More info
                    </Button>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </TableContainer>
      </PageListWrapper>

      
    </Fragment>

    
  );
}

