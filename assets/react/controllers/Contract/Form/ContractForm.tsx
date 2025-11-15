// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Box, Button, Divider, Grid2, Paper, TextField, Typography } from "@mui/material";
import { useApi } from "../../../hooks/ApiHook";
import { useNavigate } from "react-router-dom";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import { useForm } from "react-hook-form";
import { currenciesForSelector } from "../../../model/form";
import { ContractOutput, returnTypes, getReturnType } from "../../../model/contract";
import { useEffect, useState } from "react";
import axios, { AxiosError, AxiosResponse } from "axios";

export interface FormValues {
    id?:  string|number;
    label: string;
    token: string;
    goal: number;
    rate: string;
    claimMonths: number;
    description: string;
    shortDescription: string;
    projectAddress: string,
    returnType: number,
    returnMonths: number,
    minPerInvestment: number,
    file?: FileList;
    image?: FileList;
};

interface CreateOrEditContractProps {
    contract?: FormValues
}

export default function ContractForm({ contract }: CreateOrEditContractProps) {
    const { callPost } = useApi();
    const navigate = useNavigate();
    const routes = useApiRoutes();

    const [contractId, setContractId] = useState<string>(null);
    const [btnSubmitName, setBtnSubmitName] = useState<string>('Create project');

    const {
        register,
        handleSubmit,
        formState: { errors, isSubmitting },
        watch,
        setError,
        reset
    } = useForm<FormValues>({
        defaultValues: {
            label: "",
            token: "USDC",
            goal: 10,
            rate: "",
            claimMonths: 3,
            description: "",
            shortDescription: "",
            projectAddress: "",
            returnType: 0,
            returnMonths: 12,
            minPerInvestment: 10,
            file: undefined as any,
            image: undefined as any
        },
    });

    useEffect(() => {
        if (contract) {
            setContractId(contract.id as string);
            setBtnSubmitName('Update project');
            reset({
                ...contract,
                file: undefined
            });
        }
    }, [contract, reset]);

    const fileSelected: FileList = watch('file');
    const imageSelected: FileList = watch('image');

    const onSubmit = (formData: FormValues) => {

        if (!contractId && (!formData.file || formData.file.length === 0)) {
            setError("file", { type: "required", message: "You must select a project file" });
            return;
        }

        const formSendData = new FormData();
        formSendData.append('token', formData.token);
        formSendData.append('rate', formData.rate);
        formSendData.append('claimMonths', String(formData.claimMonths));
        formSendData.append('label', formData.label);
        formSendData.append('description', formData.description);
        formSendData.append('shortDescription', formData.shortDescription);
        formSendData.append('goal', String(formData.goal));
        formSendData.append('projectAddress', formData.projectAddress);
        formSendData.append('returnMonths', String(formData.returnMonths));
        formSendData.append('returnType', String(formData.returnType));
        formSendData.append('minPerInvestment', String(formData.minPerInvestment));

        if(formData.file?.length > 0){
            formSendData.append('file', formData.file[0]);
        }
        
        if(formData.image?.length > 0){
            formSendData.append('image', formData.image[0]);
        }

        const promise = (!contractId)
            ? callPost(routes.createContract, formSendData, true)
            : callPost(routes.modifyContract(contractId), formSendData, true)
        ;

        promise
            .then(
                (result: AxiosResponse<ContractOutput>) => {
                    return navigate('/app/project/' + result.data.id + '/view');
                }
            )
            .catch((error: AxiosError) => {
                if(error.response.status === 400) {
                    const validationErrors = error.response.data;
                    Object.keys(validationErrors).forEach((field: any) => {
                        setError(validationErrors[field]['label'], { type: 'server', message: validationErrors[field]['msg'] });
                    });
                } 
            })
        }

    return (
        <Box sx={{ flexGrow: 1, p: 3, maxWidth: 1200, margin: 'auto' }}>
            { !contractId ? ( <Typography variant="h4" gutterBottom align="center">
                Create new project
            </Typography> ) : (
                <Typography variant="h4" gutterBottom align="center">
                    Edit project {contract.label}
                </Typography>
            ) }
            <Divider sx={{ mb: 4 }} />

            <form onSubmit={handleSubmit(onSubmit)}>
                <Grid2 container spacing={4}>
                    <Grid2 size={{ xs: 12, md: 6 }}>
                        <Paper sx={{
                            p: 4,
                            height: '100%',
                            border: '1px solid', 
                            borderColor: 'grey.300',
                            boxShadow: 'none' 
                        }}>
                            <Typography variant="h5" sx={{ mb: 2, color: 'primary.main' }}>
                                General information
                            </Typography>
                            <Grid2 container spacing={3} sx={{ mb: 4 }}>
                                <Grid2 size={{ xs: 12, md: 6 }}>
                                    <TextField
                                        fullWidth
                                        label="Name"
                                        variant="outlined"
                                        size="small"
                                        placeholder="The project name"
                                        {...register('label', { required: 'Project name cannot be empty' })}
                                        error={!!errors.label}
                                        helperText={errors.label?.message}
                                    />
                                </Grid2>
                                <Grid2 size={{ xs: 12, md: 6 }}>
                                    <TextField
                                        fullWidth
                                        select
                                        label="Token"
                                        size="small"
                                        slotProps={{
                                            select: { native: true }
                                        }}
                                        {...register('token', { required: 'Token cannot be empty' })}
                                        error={!!errors.token}
                                        helperText={errors.token?.message}
                                    >
                                        <option value=""></option>
                                        {currenciesForSelector.map((option) => (
                                            <option key={option.value} value={option.value}>
                                                {option.label}
                                            </option>
                                        ))}
                                    </TextField>
                                </Grid2>
                                <Grid2 size={12}>
                                    <TextField
                                        fullWidth
                                        label="Project Address"
                                        variant="outlined"
                                        size="small"
                                        {...register('projectAddress', { required: 'Project address cannot be empty' })}
                                        error={!!errors.projectAddress}
                                        helperText={errors.projectAddress?.message}
                                    />
                                </Grid2>
                                <Grid2 size={{ xs: 12, md: 6 }}>
                                    <TextField
                                        fullWidth
                                        select
                                        label="Return Type"
                                        size="small"
                                        slotProps={{
                                            select: { native: true }
                                        }}
                                        {...register('returnType', { required: 'Return type cannot be empty' })}
                                        error={!!errors.returnType}
                                        helperText={errors.returnType?.message}
                                    >
                                        <option value=""></option>
                                        {returnTypes.map((option) => (
                                            <option key={option.value} value={option.value}>
                                                {option.label}
                                            </option>
                                        ))}
                                    </TextField>
                                </Grid2>
                            </Grid2>
                            <Typography variant="h5" sx={{ mb: 2, color: 'primary.main' }}>
                                Financial information
                            </Typography>
                            <Grid2 container spacing={3} sx={{ mb: 4 }}>
                                <Grid2 size={{ xs: 12, md: 6 }}>
                                    <TextField
                                        fullWidth
                                        label="Fundraising objective"
                                        variant="outlined"
                                        size="small"
                                        type="number"
                                        {...register('goal', {
                                            required: 'Fundraising objective cannot be empty',
                                            valueAsNumber: true,
                                            min: { value: 1, message: 'Fundraising objective must be greater than 0' },
                                        })}
                                        error={!!errors.goal}
                                        helperText={errors.goal?.message}
                                    />
                                </Grid2>
                                <Grid2 size={{ xs: 12, md: 6 }}>
                                    <TextField
                                        fullWidth
                                        label="Rate"
                                        variant="outlined"
                                        size="small"
                                        {...register('rate', { required: 'Rate cannot be empty' })}
                                        error={!!errors.rate}
                                        helperText={errors.rate?.message}
                                    />
                                </Grid2>
                                <Grid2 size={{ xs: 12, md: 6 }}>
                                    <TextField
                                        fullWidth
                                        label="Minimum per Investment"
                                        variant="outlined"
                                        size="small"
                                        type="number"
                                        {...register('minPerInvestment', {
                                            required: 'Minimum per investment cannot be empty',
                                            valueAsNumber: true,
                                            min: { value: 1, message: 'Minimum per investment must be greater than 0' },
                                        })}
                                        error={!!errors.minPerInvestment}
                                        helperText={errors.minPerInvestment?.message}
                                    />
                                </Grid2>
                                <Grid2 size={{ xs: 12, md: 6 }}>
                                    <TextField
                                        fullWidth
                                        label="Claim Months"
                                        variant="outlined"
                                        size="small"
                                        type="number"
                                        {...register('claimMonths', {
                                            required: 'Claim months cannot be empty',
                                            valueAsNumber: true,
                                            min: { value: 1, message: 'Claim months must be at least one' },
                                        })}
                                        error={!!errors.claimMonths}
                                        helperText={errors.claimMonths?.message}
                                    />
                                </Grid2>
                                <Grid2 size={{ xs: 12, md: 6 }}>
                                    <TextField
                                        fullWidth
                                        label="Return Months"
                                        variant="outlined"
                                        size="small"
                                        type="number"
                                        {...register('returnMonths', {
                                            required: 'Return months cannot be empty',
                                            valueAsNumber: true,
                                            min: { value: 1, message: 'Return months must be greater than 0' },
                                        })}
                                        error={!!errors.returnMonths}
                                        helperText={errors.returnMonths?.message}
                                    />
                                </Grid2>
                            </Grid2>

                            { !contractId && (<Divider sx={{ my: 3 }} /> )}

                            { !contractId && (
                                <Grid2 container>
                                    <Grid2 size={12}>
                                        <Typography variant="h6" sx={{ mb: 1 }}>
                                            Project file
                                        </Typography>
                                        <input
                                            type="file"
                                            id="file-upload"
                                            style={{ display: 'none' }}
                                            accept="application/pdf"
                                            {...register('file')}
                                        />
                                        <label htmlFor="file-upload">
                                            <Button variant="outlined" component="span">
                                                Select file
                                            </Button>
                                        </label>
                                        {fileSelected && fileSelected.length > 0 && (
                                            <Typography variant="body2" color="text.secondary" sx={{ mt: 1 }}>
                                                File selected: **{fileSelected[0].name}**
                                            </Typography>
                                        )}
                                        {errors.file && (
                                            <Typography color="error" variant="body2" sx={{ mt: 1 }}>
                                                {errors.file.message}
                                            </Typography>
                                        )}
                                    </Grid2>
                                </Grid2>
                            ) }

                            { !contractId && (
                                <Grid2 container>
                                    <Grid2 size={12}>
                                        <Typography variant="h6" sx={{ mb: 1 }}>
                                            Project image
                                        </Typography>
                                        <input
                                            type="file"
                                            id="image-upload"
                                            style={{ display: 'none' }}
                                            accept="image/png, image/jpeg, image/jpg"
                                            {...register('image')}
                                        />
                                        <label htmlFor="image-upload">
                                            <Button variant="outlined" component="span">
                                                Select image
                                            </Button>
                                        </label>
                                        {imageSelected && imageSelected.length > 0 && (
                                            <Typography variant="body2" color="text.secondary" sx={{ mt: 1 }}>
                                                Image selected: **{imageSelected[0].name}**
                                            </Typography>
                                        )}
                                        {errors.file && (
                                            <Typography color="error" variant="body2" sx={{ mt: 1 }}>
                                                {errors.file.message}
                                            </Typography>
                                        )}
                                    </Grid2>
                                </Grid2>
                            ) }
                        </Paper>
                    </Grid2>

                    <Grid2 size={{ xs: 12, md: 6 }}>
                        <Paper sx={{
                            p: 4,
                            height: '100%',
                            border: '1px solid',
                            borderColor: 'grey.300',
                            boxShadow: 'none'
                        }}>
                            <Typography variant="h5" sx={{ mb: 2, color: 'primary.main' }}>
                                Description and details
                            </Typography>
                            <Grid2 container spacing={3}>
                                <Grid2 size={12}>
                                    <TextField
                                        fullWidth
                                        label="Short Description"
                                        multiline
                                        rows={8}
                                        size="small"
                                        {...register('shortDescription', {
                                            required: 'A short description (2-3 lines) is required',
                                        })}
                                        error={!!errors.shortDescription}
                                        helperText={errors.shortDescription?.message}
                                    />
                                </Grid2>
                                <Grid2 size={12}>
                                    <TextField
                                        fullWidth
                                        label="Large Description"
                                        multiline
                                        rows={15}
                                        size="small"
                                        {...register('description', {
                                            required: 'A large description (6-7 lines) is required',
                                        })}
                                        error={!!errors.description}
                                        helperText={errors.description?.message}
                                    />
                                </Grid2>
                            </Grid2>
                        </Paper>
                    </Grid2>
                </Grid2>

                <Divider sx={{ my: 4 }} />

                <Box sx={{ display: 'flex', justifyContent: 'center' }}>
                    <Button
                        variant="contained"
                        color="primary"
                        disabled={isSubmitting}
                        type="submit"
                        size="large"
                        sx={{ minWidth: 200 }}
                    >
                        {btnSubmitName}
                    </Button>
                </Box>
            </form>
        </Box >
    );

}