import { createTheme } from "@mui/material";

const theme = createTheme({
    /*palette: {
      primary: {
        main: '#1976d2', // Azul oscuro
        light: '#63a4ff', // Azul claro
        dark: '#004ba0', // Azul más oscuro
      },
      secondary: {
        main: '#e53935', // Rojo
        light: '#ff6659', // Rojo claro
        dark: '#b71c1c', // Rojo oscuro
      },
      background: {
        default: '#f5f5f5', // Gris claro
        paper: '#ffffff', // Blanco
      },
    },
    typography: {
      fontFamily: 'Roboto, sans-serif',
      h1: {
        fontSize: '2.5rem',
        fontWeight: 700,
      },
      h2: {
        fontSize: '2rem',
        fontWeight: 700,
      },
      h3: {
        fontSize: '1.75rem',
        fontWeight: 700,
      },
      h4: {
        fontSize: '1.5rem',
        fontWeight: 700,
      },
      h5: {
        fontSize: '1.25rem',
        fontWeight: 700,
      },
      h6: {
        fontSize: '1rem',
        fontWeight: 700,
      },
      body1: {
        fontSize: '1rem',
        fontWeight: 400,
      },
      body2: {
        fontSize: '0.875rem',
        fontWeight: 400,
      },
    },
    components: {
      MuiButton: {
        styleOverrides: {
          root: {
            borderRadius: '8px',
            textTransform: 'none',
            fontWeight: 600,
          },
        },
      },
      MuiPaper: {
        styleOverrides: {
          root: {
            borderRadius: '8px',
            boxShadow: '0 2px 4px rgba(0, 0, 0, 0.1)',
          },
        },
      },
      MuiAppBar: {
        styleOverrides: {
          root: {
            backgroundColor: '#1976d2',
            color: '#ffffff',
          },
        },
      },
      MuiDrawer: {
        styleOverrides: {
          paper: {
            backgroundColor: '#f5f5f5',
            width: '240px',
          },
        },
      },
    },*/

    palette: {
      primary: {
          main: '#1976d2', // Azul primario
          light: '#64b5f6',
          dark: '#1565c0',
          contrastText: '#fff',
      },
      secondary: {
          main: '#d32f2f', // Rojo secundario
          light: '#e57373',
          dark: '#c62828',
          contrastText: '#fff',
      },
      background: {
          default: '#f0f2f5', // Gris claro de fondo
          paper: '#fff', // Blanco para los papers
      },
      text: {
          primary: '#333', // Texto principal
          secondary: '#757575', // Texto secundario
      },
  },
  typography: {
      fontFamily: '"Roboto", "Helvetica", "Arial", sans-serif',
      h1: { fontSize: '2.2rem', fontWeight: 600 },
      h2: { fontSize: '1.8rem', fontWeight: 600 },
      h3: { fontSize: '1.5rem', fontWeight: 600 },
      h4: { fontSize: '1.3rem', fontWeight: 600 },
      h5: { fontSize: '1.1rem', fontWeight: 600 },
      h6: { fontSize: '1rem', fontWeight: 600 },
      body1: { fontSize: '1rem' },
      body2: { fontSize: '0.875rem' },
  },
  components: {
      MuiButton: {
          styleOverrides: {
              root: {
                  borderRadius: '4px',
                  textTransform: 'none',
                  fontWeight: 500,
                  boxShadow: '0 1px 3px rgba(0, 0, 0, 0.12)',
                  '&:hover': { boxShadow: '0 2px 5px rgba(0, 0, 0, 0.15)' },
              },
          },
      },
      MuiPaper: {
          styleOverrides: {
              root: {
                  borderRadius: '8px',
                  boxShadow: '0 1px 3px rgba(0, 0, 0, 0.12)',
              },
          },
      },
      MuiAppBar: {
          styleOverrides: {
              root: {
                  backgroundColor: '#fff', // AppBar blanco
                  color: '#333', // Texto oscuro
                  boxShadow: '0 1px 3px rgba(0, 0, 0, 0.12)',
              },
          },
      },
      MuiDrawer: {
          styleOverrides: {
              paper: {
                  backgroundColor: '#fff',
                  width: '240px',
                  boxShadow: '1px 0 3px rgba(0, 0, 0, 0.12)',
              },
          },
      },
      MuiListItemButton: {
          styleOverrides: {
              root: {
                  '&.Mui-selected': { backgroundColor: 'rgba(0, 0, 0, 0.08)' },
                  '&:hover': { backgroundColor: 'rgba(0, 0, 0, 0.04)' },
              },
          },
      },
  },

  });

  export default theme;