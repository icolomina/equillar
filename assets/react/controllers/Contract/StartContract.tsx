// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { useNavigate, useParams } from "react-router-dom";
import { useApi } from "../../hooks/ApiHook";
import { useApiRoutes } from "../../hooks/ApiRoutesHook";
import { Box, Button, CircularProgress, Divider, Grid2, TextField, Typography } from "@mui/material";
import { useEffect, useState } from "react";
import { AxiosResponse } from "axios";
import { ContractOutput, ContractReturnTypes, returnTypes } from "../../model/contract";

interface FormData {
    projectAddress: string,
    returnType: number,
    returnMonths: number | string,
    minPerInvestment: number
}

export default function StartContract() {
    const { callGet, callPatch } = useApi();
    const apiRoutes = useApiRoutes();
    const [loading, setLoading] = useState<boolean>(false);
    const [contract, setContract] = useState<ContractOutput>(null);
    const params = useParams();
    const navigate = useNavigate();

    const [formData, setFormData] = useState<FormData>({
        projectAddress: '',
        returnType: 0,
        returnMonths: '',
        minPerInvestment: 10
    });

    const handleChange = (event: any) => {
        const { name, value } = event.target;
        if (name == 'returnType' && value == 3) {
            formData.returnMonths = 0;
        }

        setFormData(
            (previousFormData) => ({ ...previousFormData, [name]: name !== 'projectAddress' ? Number(value) : value })
        );
    }

    const handleSubmit = () => {
        setLoading(true);
        callPatch(apiRoutes.startContract(params.id), formData).then(
            () => {
                setLoading(false);
                return navigate('/app/home-company');
            }
        )
    }

    useEffect(() => {
        setLoading(true);
        callGet<object, ContractOutput>(apiRoutes.editContract(params.id), {}).then(
            (response: AxiosResponse) => {
                console.log(response.data);
                setContract(response.data);
                setLoading(false);
            }
        )
    }, []);

    if (loading || !contract) {
        return (
            <Box
                sx={{
                    display: 'flex',
                    flexDirection: 'column',
                    justifyContent: 'center',
                    alignItems: 'center',
                    minHeight: '200px',
                }}
            >
                <CircularProgress sx={{ mb: 2 }} />
                <Typography variant="subtitle1" color="textSecondary">
                    Deploying contract. This may take a few seconds...
                </Typography>
            </Box>
        );
    }
    else {
        return (
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
                        <Typography variant="h4">
                            Activar proyecto {contract.label}
                        </Typography>
                    </Grid2>
                    <Grid2 size={12}>
                        <Divider sx={{ marginY: 2 }} />
                    </Grid2>
                    <Grid2 container size={{ xs: 12, md: 6 }} spacing={3}>
                        <TextField
                            fullWidth
                            name="projectAddress"
                            label="Project Stellar Address"
                            variant="outlined"
                            required
                            size="small"
                            placeholder="Project Stellar Address where the funds will be sent to"
                            value={formData.projectAddress}
                            onChange={handleChange}
                        />

                        <TextField
                            id="outlined-select-currency-native"
                            fullWidth
                            name="returnType"
                            select
                            label="Return type"
                            size="small"
                            slotProps={{
                                select: {
                                    native: true,
                                },
                            }}
                            value={formData.returnType}
                            onChange={handleChange}
                        >
                            {returnTypes.map((option) => (
                                <option key={option.value} value={option.value}>
                                    {option.label}
                                </option>
                            ))}
                        </TextField>
                    </Grid2>
                    <Grid2 container size={{ xs: 12, md: 6 }} spacing={3}>
                        <TextField
                            fullWidth
                            label="Return months"
                            name="returnMonths"
                            variant="outlined"
                            required
                            size="small"
                            placeholder="Number of months to return gains"
                            value={formData.returnMonths}
                            onChange={handleChange}
                            disabled={formData.returnType == 3}
                            type="number"
                        />

                        <TextField
                            fullWidth
                            label="Minimum per investment"
                            name="minPerInvestment"
                            variant="outlined"
                            required
                            size="small"
                            value={formData.minPerInvestment}
                            onChange={handleChange}
                            type="number"
                        />
                    </Grid2>
                </Grid2>
                <Grid2 size={12}>
                    <Divider sx={{ marginY: 2 }} />
                </Grid2>
                <Grid2 container sx={{ display: 'flex', justifyContent: 'flex-start', marginTop: 2 }}>
                    <Button variant="contained" color="primary" onClick={handleSubmit}>
                        Enable project
                    </Button>
                </Grid2>
            </Box>
        );
    }
}