import { Fragment, useEffect, useState } from "react";
import { useApi } from "../hooks/ApiHook";
import { GetAvailableContractsPath } from "../services/Api/Investment/ApiRoutes";
import { AxiosError, AxiosResponse } from "axios";
import {
  Backdrop,
  Box,
  Button,
  Card,
  CardActions,
  CardContent,
  CardMedia,
  CircularProgress,
  Divider,
  Grid2,
  Typography,
  useTheme,
} from "@mui/material";

import { ContractInvestment } from "./HomeCompany";
import { useNavigate } from "react-router-dom";
import { object } from "prop-types";
import { UseQueryResult } from "@tanstack/react-query";

export default function HomeInvestor() {
  const { useGetQuery } = useApi();
  const navigate = useNavigate();
  const { isLoading, data, error } = useGetQuery<object, ContractInvestment[]>(GetAvailableContractsPath, {});
  const [availableContracts, setAvailableContracts] = useState<ContractInvestment[]>([]);

  useEffect(() => {
    if (data) {
      setAvailableContracts(data);
    }
  }, [data]);

  const handleSendInvestment = (projectId: number) => {
    return navigate('/app/project/' + projectId + '/invest');
  }

  const theme = useTheme();

  if (isLoading) {
    return (
      <Fragment>
        <Backdrop
          sx={(theme) => ({ color: "#fff", zIndex: theme.zIndex.drawer + 1 })}
          open={isLoading}
        //onClick={handleClose}
        >
          <CircularProgress color="inherit" />
        </Backdrop>
      </Fragment>
    );
  } else {
    if (availableContracts.length === 0) {
      return (
        <Fragment>
          <Box
            sx={{
              p: 2,
              mt: 2,
              display: "flex",
              flexDirection: "column",
              justifyContent: "center",
              alignItems: "center",
              height: "100%",
            }}
          >
            <Typography variant="h6" sx={{ mb: 2 }}>
              Por el momento, no existen proyectos en los que invertir
            </Typography>
          </Box>
        </Fragment>
      );
    }
  }

  /*return (
    <Fragment>
      <Box sx={{ flexGrow: 1, p: 2 }}>
        <Grid2 container spacing={2}>
          <Grid2
            size={12}
            sx={{
              display: "flex",
              justifyContent: "space-between",
              alignItems: "center",
            }}
          >
            <Typography variant="h4">Listado de proyectos disponibles</Typography>
          </Grid2>
          <Grid2 size={12}>
            <Divider sx={{ marginY: 2 }} />
          </Grid2>
              {availableContracts.map((ac: ContractInvestment) => (
                <Grid2 size={{ xs: 12, md: 4 }} key={ac.id}>
                  <Card sx={{ maxWidth: 345 }}>
                    <CardMedia
                      component="img"
                      alt="green iguana"
                      height="140"
                      image="https://127.0.0.1:8000/images/card.jpg"
                    />
                    <CardContent>
                      <Typography gutterBottom variant="h5" component="div">
                        { ac.label } <small>({ ac.tokenCode })</small>
                      </Typography>
                      <Typography
                        variant="body2"
                        sx={{ color: "text.secondary" }}
                      >
                       { ac.description }
                      </Typography>
                    </CardContent>
                    <CardActions>
                      <Button size="small" onClick={ () => handleSendInvestment(ac.id)}>I want to invest</Button>
                      <Button size="small">More info</Button>
                    </CardActions>
                  </Card>
                </Grid2>
              ))}
            </Grid2>
      </Box>
    </Fragment>
  );*/

  return (
    <Fragment>
      <Box sx={{ flexGrow: 1, p: 4 }}>
        <Grid2 container spacing={4}>
          <Grid2 size={12}>
            <Typography variant="h4" gutterBottom>
              Listado de proyectos disponibles
            </Typography>
          </Grid2>
          {availableContracts.map((ac: ContractInvestment) => (
            <Grid2 size={{ xs: 12, sm: 6, md: 3 }} key={ac.id}>
              <Card
                sx={{
                  height: '100%',
                  display: 'flex',
                  flexDirection: 'column',
                  boxShadow: "0 4px 8px 0 rgba(0, 0, 0, 0.2)",
                  transition: "0.3s",
                  "&:hover": {
                    boxShadow: "0 8px 16px 0 rgba(0, 0, 0, 0.2)",
                  },
                }}
              >
                <CardMedia
                  component="img"
                  alt="green iguana"
                  height="140"
                  image="https://127.0.0.1:8000/images/card.jpg"
                />
                <CardContent sx={{ flexGrow: 1 }}>
                  <Typography gutterBottom variant="h5" component="div">
                    {ac.label}
                  </Typography>
                  <Typography variant="body2" color="text.secondary" gutterBottom>
                    {ac.shortDescription}
                  </Typography>
                  <Divider sx={{ marginY: 1 }} />
                  <Typography variant="body2" color="text.secondary">
                    <strong>Token: </strong>{ac.tokenCode}
                  </Typography>
                </CardContent>
                <CardActions
                  sx={{
                    display: "flex",
                    justifyContent: "flex-end",
                    alignItems: "center",
                    padding: 2,
                  }}
                >
                  <Button
                    size="medium"
                    variant="contained"
                    color="primary"
                    onClick={ () => handleSendInvestment(ac.id)}
                    sx={{
                      padding: '8px 16px',
                      fontSize: '0.875rem',
                    }}
                  >
                    More Info
                  </Button>
                </CardActions>
              </Card>
            </Grid2>
          ))}
        </Grid2>
      </Box>
    </Fragment>

  )
}
