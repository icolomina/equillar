import { Box, Button, Divider, Grid2, TextField, Typography } from "@mui/material";
import { ChangeEvent, useState } from "react";
import { useApi } from "../../../hooks/ApiHook";
import { CreateContractInvestmentPath } from "../../../services/Api/Investment/ApiRoutes";
import { useNavigate } from "react-router-dom";

interface FormData {
    token: string;
    rate: string;
    claimMonths: string;
    label: string;
    description: string;
    shortDescription: string;
    goal: string;
}

export default function CreateInvestmentProject() {
    const {callPost} = useApi(); 
    const navigate = useNavigate();
    const [formData, setFormData] = useState<FormData>({ 
        token: 'USDC', 
        rate: '', 
        claimMonths: '', 
        label: '', 
        description: '', 
        shortDescription: '',
        goal: '', 
    });

    const [file, setFile] = useState<File>();

    const handleChange = (event: any) => {
        const { name, value } = event.target;
        setFormData ( (previousFormData) => ({ ...previousFormData, [name]: value}) )
    }

    const handleFileChange = (e: ChangeEvent<HTMLInputElement>) => {
        if(e.target.files) {
            setFile(e.target.files[0]);
        }
    }

    const handleSubmit = () => {
        
        const formSendData = new FormData();
        formSendData.append('token', formData.token);
        formSendData.append('rate', formData.rate);
        formSendData.append('claimMonths', formData.claimMonths);
        formSendData.append('label', formData.label);
        formSendData.append('description', formData.description);
        formSendData.append('shortDescription', formData.shortDescription);
        formSendData.append('goal', formData.goal);
        formSendData.append('file', file);


        callPost(CreateContractInvestmentPath, formSendData, true).then(
            () => {
                return navigate("/app");
            }
        )
    }

    const currencies = [
        {
          value: 'USDC',
          label: 'Circle - USCD',
        }
      ];

    const investmentReturnTypes = [
        {
            value: '1',
            label: 'Credito inverso'
        },
        {
            value: '2',
            label: 'Cupon'
        },
        {
            value: '3',
            label: 'Pago único'
        }
    ]

    /*return (
        <Box sx={{ flexGrow: 1, p: 2 }} >
            <Grid2 container spacing={2} >
                <Grid2 size={12} sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                        <Typography variant="h4">Crear nuevo proyecto</Typography>
                </Grid2>
                <Grid2 size={12}>
                    <Divider sx={{ marginY: 2 }} />
                </Grid2>
                <Grid2 container size={{ xs: 12, md: 6 }} spacing={3}>
                        <TextField
                            fullWidth
                            name="label"
                            label="Nombre"
                            variant="outlined"
                            required
                            size="small"
                            placeholder="Nombre del proyecto"
                            value={formData.label}
                            onChange={handleChange}
                        />
                    
                        <TextField
                            id="outlined-select-currency-native"
                            fullWidth
                            name="token"
                            select
                            label="Moneda"
                            size="small"
                            slotProps={{
                                select: {
                                native: true,
                                },
                            }}
                            value={formData.token}
                            onChange={handleChange}
                        >
                            {currencies.map((option) => (
                                <option key={option.value} value={option.value}>
                                {option.label}
                            </option>
                            ))}
                        </TextField>
                </Grid2>
                <Grid2 container size={{ xs: 12, md: 6 }} spacing={3}>                          
                        <TextField
                            fullWidth
                            label="Rate"
                            name="rate"
                            variant="outlined"
                            required
                            size="small"
                            value={formData.rate}
                            onChange={handleChange}
                        />

                        <TextField
                            fullWidth
                            label="Claim Months"
                            name="claimMonths"
                            variant="outlined"
                            required
                            size="small"
                            type="number"
                            value={formData.claimMonths}
                            onChange={handleChange}
                        />
                </Grid2>
                <Grid2 container size={{ xs: 12, md: 6 }} spacing={3}>
                        <TextField
                            fullWidth
                            label="Objetivo a recaudar"
                            name="goal"
                            variant="outlined"
                            required
                            size="small"
                            type="number"
                            value={formData.goal}
                            onChange={handleChange}
                        />
                </Grid2>
                <Grid2 container size={12} spacing={3}>
                    <TextField
                            fullWidth
                            label="Description"
                            variant="outlined"
                            name="description"
                            multiline
                            rows={4}
                            required
                            value={formData.description}
                            onChange={handleChange}
                            size="small"
                        />
                </Grid2>
                <Grid2 container size={12} spacing={3}>
                    <TextField
                        type="file"
                        variant="outlined"
                        fullWidth
                        margin="normal"
                        helperText="Selecciona el fichero del proyecto"
                        onChange={handleFileChange}
                    />
                </Grid2>
            </Grid2>
            <Grid2 size={12}>
                <Divider sx={{ marginY: 2 }} />
            </Grid2>
            <Grid2 container sx={{ display: 'flex', justifyContent: 'flex-start', marginTop: 2 }}>
                <Button variant="contained" color="primary" onClick={handleSubmit}>
                    Solicitar creación de nuevo proyecto
                </Button>
            </Grid2>
        </Box>
    )*/

        return (
            <Box sx={{ flexGrow: 1, p: 3 }}>
    <Grid2 container spacing={3}>
        <Grid2 size={{xs: 12}}>
            <Typography variant="h4">Crear nuevo proyecto</Typography>
            <Divider sx={{ mt: 1, mb: 3 }} />
        </Grid2>

        {/* Sección de Información Básica */}
        <Grid2 size= {{ xs: 12, md: 6 }}>
            <Typography variant="h6" sx={{ mb: 1 }}>Información básica</Typography>
            <TextField fullWidth label="Nombre" name="label" variant="outlined" required size="small" placeholder="Nombre del proyecto" value={formData.label} onChange={handleChange} sx={{ mb: 2 }} />
            <TextField fullWidth select label="Moneda" name="token" size="small" value={formData.token} onChange={handleChange} SelectProps={{ native: true }} sx={{ mb: 2 }}>
                {currencies.map((option) => (
                    <option key={option.value} value={option.value}>{option.label}</option>
                ))}
            </TextField>
            <TextField fullWidth label="Objetivo a recaudar" name="goal" variant="outlined" required size="small" type="number" value={formData.goal} onChange={handleChange} sx={{ mb: 2 }} />
            <TextField fullWidth label="Rate" name="rate" variant="outlined" required size="small" value={formData.rate} onChange={handleChange} sx={{ mb: 2 }} />
            <TextField fullWidth label="Claim Months" name="claimMonths" variant="outlined" required size="small" type="number" value={formData.claimMonths} onChange={handleChange} sx={{ mb: 2 }} />
        </Grid2>

        {/* Sección de Detalles del Proyecto */}
        <Grid2 size={{ xs: 12, md: 6 }}>
            <Typography variant="h6" sx={{ mb: 1 }}>Detalles del proyecto</Typography>
            <TextField fullWidth label="Descripción larga" name="description" multiline rows={8} required value={formData.description} onChange={handleChange} size="small" sx={{ mb: 2 }} />
            <TextField fullWidth label="Descripción corta" name="shortDescription" multiline rows={4} required value={formData.shortDescription} onChange={handleChange} size="small" sx={{ mb: 2 }} />
        </Grid2>

        <Grid2 size={{xs: 12}}>
            <Divider sx={{ my: 1 }} /> {/* Añade margen vertical */}
        </Grid2>

        {/* Sección de Archivo del Proyecto */}
        <Grid2 size={{ xs: 12 }}>
            <Typography variant="h6" sx={{ mb: 1 }}>Archivo del proyecto</Typography>
            <input
                type="file"
                id="file-upload"
                style={{ display: 'none' }}
                onChange={handleFileChange}
            />
            <label htmlFor="file-upload">
                <Button variant="outlined" component="span">
                    Seleccionar archivo
                </Button>
            </label>
            {file && (
                <Typography variant="body1" sx={{ mt: 1 }}>
                    Archivo seleccionado: {file.name}
                </Typography>
            )}
        </Grid2>

        <Grid2 size={{xs: 12}}>
            <Divider sx={{ my: 3 }} /> {/* Añade margen vertical */}
        </Grid2>

        {/* Botón de Envío */}
        <Grid2 size={{ xs: 12 }} sx={{ mt: 3, display: 'flex', justifyContent: 'flex-start' }}>
            <Button variant="contained" color="primary" onClick={handleSubmit}>
                Solicitar creación de nuevo proyecto
            </Button>
        </Grid2>
    </Grid2>
</Box>
        );

}