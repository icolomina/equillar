// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Container, TextField, Button, Typography, Box, Paper, Avatar, Grid2, CircularProgress, List, ListItem, ListItemIcon, Divider, useTheme, ListItemText, Collapse, Alert } from '@mui/material';
import { useAuth } from '../../hooks/AuthHook';
import { useState } from 'react';
import { AxiosError, AxiosResponse } from 'axios';
import { useNavigate } from 'react-router-dom';
import LockOutlinedIcon from '@mui/icons-material/LockOutlined';
import BusinessIcon from '@mui/icons-material/Business';
import AccountBalanceWalletIcon from '@mui/icons-material/AccountBalanceWallet';
import SearchIcon from '@mui/icons-material/Search';
import SavingsIcon from '@mui/icons-material/Savings';
import TrendingUpIcon from '@mui/icons-material/TrendingUp';


export interface LoginProps {
  endpoint: string
}

interface LoginResponse {
  token: string,
  role: string,
  role_type: string,
  name: string
}

export default function SignIn() {

  const { login } = useAuth();
  const navigate = useNavigate();
  const theme = useTheme();

  const [username, setUsername] = useState<string>('');
  const [password, setPassword] = useState<string>('');
  const [loading, setLoading] = useState(false);
  const [authError, setAuthError] = useState<string>(null);

  const handleUsername = (username: string) => {
    setUsername(username);
  }

  const handlePassword = (password: string) => {
    setPassword(password);
  }

  const handleLogin = () => {
    setLoading(true);
    login(username, password).then(
      (result: AxiosResponse<LoginResponse>) => {
        localStorage.setItem('token', result.data.token);
        localStorage.setItem('role', result.data.role);
        localStorage.setItem('role_type', result.data.role_type);
        localStorage.setItem('name', result.data.name);
        setLoading(false);
        navigate('/app/');
      }
    ).catch(
      (r: AxiosError) => {
        if(r.status === 401) {
          setAuthError('Bad Credentials');
        }
        if(r.status === 400) {
          setAuthError('Missing credentials. Review user and pass fields');
        }
        setLoading(false);
      }
    )
  }

  return (
    <Container
      component="main"
      maxWidth="lg" 
      sx={{
        height: '100vh',
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        backgroundImage: 'url("data:image/svg+xml,%3Csvg width=\'6\' height=\'6\' viewBox=\'0 0 6 6\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 0L6 6M6 0L0 6\' stroke=\'%23e0e0e0\' stroke-width=\'1\' fill=\'none\'/%3E%3C/svg%3E")', // Patrón de líneas diagonal muy claro
        backgroundSize: 'auto',
        backgroundPosition: 'center',
        backgroundColor: theme.palette.background.default,
      }}
    >
      <Paper
        elevation={5}
        sx={{
          display: 'flex',
          maxWidth: '900px',
          borderRadius: theme.shape.borderRadius * 2,
          overflow: 'hidden',
        }}
      >
        <Grid2 container>
          <Grid2
            size={{ xs: 12, md: 6 }}
            sx={{
              backgroundColor: theme.palette.primary.dark,
              color: theme.palette.common.white,
              padding: theme.spacing(4),
              display: 'flex',
              flexDirection: 'column',
              justifyContent: 'center',
              alignItems: 'flex-start',
            }}
          >
            <Typography
              variant="h3" // Un tamaño más grande para el título
              component="h1" // Semánticamente es el título principal
              gutterBottom
              sx={{
                fontFamily: 'Montserrat, sans-serif', // Una fuente moderna (asegúrate de tenerla en tu proyecto o usar una web font)
                color: theme.palette.secondary.light, // Un color que contraste y destaque
                textShadow: '2px 2px 4px rgba(0, 0, 0, 0.2)', // Ligera sombra para profundidad
                marginBottom: theme.spacing(3), // Más espacio debajo del título
              }}
            >
              Welcome to Equillar
            </Typography>
            <Typography component="h2" variant="h4" gutterBottom>
              Boost your Finances with Intelligence
            </Typography>
            <Typography variant="subtitle1" color="inherit" sx={{ mb: 2 }}>
              Discover a world of opportunities for businesses and investors.
            </Typography>
            <Divider sx={{ width: '100%', backgroundColor: theme.palette.secondary.main, mb: 2 }} />
            <Typography variant="h6" color="inherit" sx={{ mb: 1 }}>
              For Businesses:
            </Typography>
            <List sx={{ mt: 2 }}>
              <ListItem sx={{ padding: theme.spacing(0.5, 0) }}>
                <ListItemIcon sx={{ minWidth: 'auto', marginRight: theme.spacing(1), color: theme.palette.secondary.light }}>
                  <BusinessIcon />
                </ListItemIcon>
                <ListItemText primary="Register projects to seek funding" />
              </ListItem>
              <ListItem sx={{ padding: theme.spacing(0.5, 0) }}>
                <ListItemIcon sx={{ minWidth: 'auto', marginRight: theme.spacing(1), color: theme.palette.secondary.light }}>
                  <AccountBalanceWalletIcon />
                </ListItemIcon>
                <ListItemText primary="Receiving funding through stablecoins on the Stellar network" />
              </ListItem>
            </List>
            <Typography variant="h6" color="inherit" sx={{ mt: 3, mb: 1 }}>
              For Investors:
            </Typography>
            <List sx={{ mt: 2 }}>
              <ListItem sx={{ padding: theme.spacing(0.5, 0) }}>
                <ListItemIcon sx={{ minWidth: 'auto', marginRight: theme.spacing(1), color: theme.palette.secondary.light }}>
                  <SearchIcon />
                </ListItemIcon>
                <ListItemText primary="Search for projects to invest in" />
              </ListItem>
              <ListItem sx={{ padding: theme.spacing(0.5, 0) }}>
                <ListItemIcon sx={{ minWidth: 'auto', marginRight: theme.spacing(1), color: theme.palette.secondary.light }}>
                  <SavingsIcon />
                </ListItemIcon>
                <ListItemText primary="Using self-custodial stablecoins to make contributions" />
              </ListItem>
              <ListItem sx={{ padding: theme.spacing(0.5, 0) }}>
                <ListItemIcon sx={{ minWidth: 'auto', marginRight: theme.spacing(1), color: theme.palette.secondary.light }}>
                  <TrendingUpIcon />
                </ListItemIcon>
                <ListItemText primary="Receive capital gains automatically" />
              </ListItem>
            </List>
          </Grid2>
          <Grid2
            size={{ xs: 12, md: 6 }}
            sx={{
              padding: theme.spacing(4),
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <Avatar sx={{ m: 1, bgcolor: 'primary.main' }}>
              <LockOutlinedIcon />
            </Avatar>
            <Typography component="h1" variant="h5" sx={{ mb: 2 }}>
              Iniciar Sesión
            </Typography>
            
            <Collapse in={!!authError} sx={{ width: '100%', mb: 2 }}>
              <Alert severity="error">
                {authError}
              </Alert>
            </Collapse>
            <Box component="form" sx={{ width: '100%', mt: 1 }}>
              <Grid2 container spacing={2}>
                <Grid2 size={12}>
                  <TextField
                    required
                    fullWidth
                    id="email"
                    label="Email"
                    name="email"
                    autoComplete="email"
                    autoFocus
                    variant="outlined"
                    onChange={(e) => handleUsername(e.target.value)}
                  />
                </Grid2>
                <Grid2 size={12}>
                  <TextField
                    required
                    fullWidth
                    name="password"
                    label="Password"
                    type="password"
                    id="password"
                    autoComplete="current-password"
                    variant="outlined"
                    onChange={(e) => handlePassword(e.target.value)}
                  />
                </Grid2>
              </Grid2>
              <Button
                type="button"
                fullWidth
                variant="contained"
                sx={{ mt: 3, mb: 2, py: 1.5 }}
                onClick={() => handleLogin()}
              >
                {loading ? <CircularProgress size={24} color="inherit" /> : 'Log In'}
              </Button>
            </Box>
          </Grid2>
        </Grid2>
      </Paper>
    </Container>
  );
}