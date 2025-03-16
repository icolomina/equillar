import { Container, TextField, Button, Typography, Box, Paper, Avatar, Grid2, CircularProgress } from '@mui/material';
import { useAuth } from '../../hooks/AuthHook';
import { useState } from 'react';
import { AxiosResponse } from 'axios';
import { useLocation, useNavigate } from 'react-router-dom';
import LockOutlinedIcon from '@mui/icons-material/LockOutlined';


export interface LoginProps {
    endpoint: string
}

interface LoginResponse {
  token: string,
  role: string
}

export default function SignIn () {

    const {login} = useAuth();
    const navigate = useNavigate();

    const [username, setUsername] = useState<string>('');
    const [password, setPassword] = useState<string>('');
    const [loading, setLoading] = useState(false);

    const handleUsername = (username: string) => {
        setUsername(username);
    }

    const handlePassword = (password: string) => {
        setPassword(password);
    }

    const handleLogin = () => {
      setLoading(true); 
      login(username, password, '').then(
        (result: AxiosResponse<LoginResponse>) => {
          console.log(result.data);
          localStorage.setItem('token', result.data.token);
          localStorage.setItem('role', result.data.role);
          setLoading(false); 
          navigate('/app/');
        }
      )
    }

    /*return (
        <Container component="main" maxWidth="xs">
          <Box
            sx={{
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
              mt: 8,
            }}
          >
            <Typography component="h1" variant="h5">
              Iniciar Sesión
            </Typography>
            <Box component="form" sx={{ mt: 1 }}>
              <TextField
                margin="normal"
                required
                fullWidth
                id="email"
                label="Correo Electrónico"
                name="email"
                autoComplete="email"
                autoFocus
                onChange={(e) => handleUsername(e.target.value)}
              />
              <TextField
                margin="normal"
                required
                fullWidth
                name="password"
                label="Contraseña"
                type="password"
                id="password"
                autoComplete="current-password"
                onChange={(e) => handlePassword(e.target.value)}
              />
              <Button
                type="button"
                fullWidth
                variant="contained"
                sx={{ mt: 3, mb: 2 }}
                onClick={() => handleLogin()}
              >
                Iniciar Sesión
              </Button>
            </Box>
          </Box>
        </Container>
      );*/

      return (
        <Container component="main" maxWidth="sm">
      <Paper
        elevation={3}
        sx={{
          mt: 8,
          p: 4,
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
        }}
      >
        <Avatar sx={{ m: 1, bgcolor: 'primary.main' }}>
          <LockOutlinedIcon />
        </Avatar>
        <Typography component="h1" variant="h5" sx={{ mb: 2 }}>
          Iniciar Sesión
        </Typography>
        <Box component="form" sx={{ width: '100%', mt: 1 }}>
          <Grid2 container spacing={2}>
            <Grid2 size={12}>
              <TextField
                required
                fullWidth
                id="email"
                label="Correo Electrónico"
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
                label="Contraseña"
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
            {loading ? <CircularProgress size={24} color="inherit" /> : 'Iniciar Sesión'}
          </Button>
        </Box>
      </Paper>
    </Container>
      )
}