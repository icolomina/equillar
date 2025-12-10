// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Fragment } from "react";
import { useApi } from "../../hooks/ApiHook";
import { useApiRoutes } from "../../hooks/ApiRoutesHook";
import {
  Backdrop,
  Box,
  Button,
  CardActions,
  CardContent,
  CardMedia,
  CircularProgress,
  Divider,
  Grid2,
  Typography,
  useTheme,
} from "@mui/material";

import { useNavigate } from "react-router-dom";
import { ContractOutput } from "../../model/contract";
import { useQuery } from "@tanstack/react-query";
import axios, { AxiosError, AxiosResponse } from "axios";
import { ProjectForInvesting } from "../Theme/Styled/Card";
import { EmptyElementsBox } from "../Theme/Styled/Box";

export default function HomeInvestor() {
  const { callGet } = useApi();
  const navigate = useNavigate();
  const apiRoutes = useApiRoutes();

  const query = useQuery(
    {
      queryKey: ['get-user-available-contracts'],
      queryFn: async () => {
        const result: AxiosResponse<ContractOutput[]> | AxiosError = await callGet<object, ContractOutput[]>(apiRoutes.getAvailableContracts, {});
        if (!axios.isAxiosError(result)) {
          return result.data;
        }

        throw new Error(result.message);
      },
      retry: 0
    }
  )

  const handleSendInvestment = (projectId: string) => {
    return navigate('/app/project/' + projectId + '/invest');
  }

  if (query.isLoading) {
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
  } else {
    if (query.data?.length === 0) {
      return (
        <Fragment>
          <EmptyElementsBox>
            <Typography variant="h6" sx={{ mb: 2 }}>
              Por el momento, no existen proyectos en los que invertir
            </Typography>
          </EmptyElementsBox>
        </Fragment>
      );
    }
  }
  return (
    <Fragment>
      <Box sx={{ flexGrow: 1, p: 4 }}>
        <Grid2 container spacing={4}>
          <Grid2 size={12}>
            <Typography variant="h4" gutterBottom>
              Available projects list
            </Typography>
          </Grid2>
          {query.data.map((ac: ContractOutput) => (
            <Grid2 size={{ xs: 12, sm: 6, md: 3 }} key={ac.id}> {/* md={3} para 12/3 = 4 tarjetas por fila */}

              <ProjectForInvesting>
                <CardMedia component="img" alt={ac.label} image={ac.imageUrl} />
                <CardContent>
                  <Box>
                  <Typography variant="h6" gutterBottom fontWeight="bold">{ac.label}</Typography>
                  <Typography variant="body2" color="text.secondary" gutterBottom>{ac.shortDescription}</Typography>
                  </Box>
                  <Divider />
                  <Box className="paramsContainer">
                    <Typography className="paramLabel">Token:</Typography>
                    <Typography className="paramValue">{ac.tokenContract.code}</Typography>

                    <Typography className="paramLabel">Expected rate:</Typography>
                    <Typography className="paramValue">{ac.rate}%</Typography>

                    <Typography className="paramLabel">Months to wait for payments:</Typography>
                    <Typography className="paramValue">{ac.claimMonths}</Typography>
                  </Box>
                </CardContent>
                <CardActions>
                  <Button fullWidth variant="contained" color="primary" onClick={() => handleSendInvestment(ac.id)}>
                    I am interested in investing
                  </Button>
                </CardActions>
              </ProjectForInvesting>
            </Grid2>
          ))}
        </Grid2>
      </Box>
    </Fragment>
  );
}

