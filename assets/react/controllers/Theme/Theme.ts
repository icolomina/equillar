/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
import { createTheme } from "@mui/material";

const tempPrimaryMain = '#1976d2'; // Tu color primario

const theme = createTheme({
    palette: {
        primary: {
            main: tempPrimaryMain,
            light: '#64b5f6',
            dark: '#1565c0',
            contrastText: '#fff',
        },
        secondary: {
            main: '#d32f2f',
            light: '#e57373',
            dark: '#c62828',
            contrastText: '#fff',
        },
        background: {
            default: '#f0f2f5',
            paper: '#fff',
        },
        text: {
            primary: '#202124', // Google Dark Gray
            secondary: '#5f6368', // Google Medium Gray
        },
        action: {
            active: 'rgba(0, 0, 0, 0.54)',
            hover: 'rgba(0, 0, 0, 0.06)', // Un poco más visible
            hoverOpacity: 0.06,
            selected: `rgba(${parseInt(tempPrimaryMain.slice(1, 3), 16)}, ${parseInt(tempPrimaryMain.slice(3, 5), 16)}, ${parseInt(tempPrimaryMain.slice(5, 7), 16)}, 0.08)`,
            selectedOpacity: 0.08,
            disabled: 'rgba(0, 0, 0, 0.26)',
            disabledBackground: 'rgba(0, 0, 0, 0.12)',
            focus: 'rgba(0, 0, 0, 0.12)',
            focusOpacity: 0.12,
        },
        divider: 'rgba(0,0,0,0.08)', // Divisor más sutil
    },
    typography: {
        fontFamily: '"Roboto", "Helvetica", "Arial", sans-serif',
        h1: { fontSize: '2.2rem', fontWeight: 600 },
        h2: { fontSize: '1.8rem', fontWeight: 600 },
        h3: { fontSize: '1.5rem', fontWeight: 600 },
        h4: { fontSize: '1.3rem', fontWeight: 600 },
        h5: { fontSize: '1.1rem', fontWeight: 600 },
        h6: { fontSize: '1rem', fontWeight: 600 }, // Google a menudo usa 500 para h6
        body1: { fontSize: '1rem' },
        body2: { fontSize: '0.875rem' }, // Usado en el menú
    },
    components: {
        MuiButton: {
            styleOverrides: {
                root: ({ theme: _theme }) => ({ // Renombrar theme a _theme para evitar conflicto con el theme exterior si es necesario
                    borderRadius: '8px',
                    textTransform: 'none',
                    fontWeight: 500,
                    padding: '8px 22px',
                    boxShadow: _theme.shadows[1],
                    '&:hover': {
                        boxShadow: _theme.shadows[2],
                    },
                }),
            },
        },
        MuiPaper: {
            styleOverrides: {
                root: ({ theme: _theme }) => ({
                    borderRadius: '8px',
                    boxShadow: _theme.shadows[1],
                }),
            },
        },
        MuiAppBar: {
            styleOverrides: {
                root: ({ theme: _theme }) => ({
                    backgroundColor: _theme.palette.background.paper,
                    color: _theme.palette.text.primary,
                    boxShadow: 'none',
                    borderBottom: `1px solid ${_theme.palette.divider}`,
                }),
            },
        },
        MuiDrawer: { // Aplicará si tu menú lateral es un MuiDrawer real
            styleOverrides: {
                paper: ({ theme: _theme }) => ({
                    backgroundColor: _theme.palette.background.paper,
                    width: '260px',
                    boxShadow: 'none',
                    borderRight: `1px solid ${_theme.palette.divider}`,
                }),
            },
        },
        MuiListItemButton: { // Override global para ListItemButton
            styleOverrides: {
                root: ({ theme: _theme }) => ({
                    borderRadius: '4px', // Redondeo general. El menú lateral lo sobreescribirá.
                    '&.Mui-selected': {
                        // El menu lateral CompanyMenu lo sobreescribirá con azul.
                        // Esto es para otros usos de ListItemButton si necesitan un fondo seleccionado más sutil.
                        backgroundColor: _theme.palette.action.selected,
                        '& .MuiListItemIcon-root': {
                            color: _theme.palette.primary.main, // Icono en color primario si es seleccionado sutilmente
                        },
                    },
                    '&:hover': {
                        backgroundColor: _theme.palette.action.hover,
                    },
                }),
            },
        },
        MuiMenuItem: {
            styleOverrides: {
                root: ({ theme: _theme }) => ({
                    borderRadius: '4px',
                    paddingTop: _theme.spacing(0.75),
                    paddingBottom: _theme.spacing(0.75),
                    '&:hover': {
                        backgroundColor: _theme.palette.action.hover,
                    },
                    '&.Mui-selected': {
                        backgroundColor: _theme.palette.action.selected,
                        '&:hover': {
                            backgroundColor: _theme.palette.action.selected,
                        }
                    }
                }),
            }
        },
        MuiMenu: {
            styleOverrides: {
                paper: ({ theme: _theme }) => ({
                    borderRadius: '8px',
                    boxShadow: _theme.shadows[3],
                })
            }
        },
        MuiListItemIcon: {
            styleOverrides: {
                root: ({ theme: _theme }) => ({
                    minWidth: 'auto',
                    marginRight: _theme.spacing(2), // Ajusta según el diseño del CompanyMenu
                    // color: _theme.palette.action.active, // Color por defecto, se sobreescribe en CompanyMenu
                }),
            },
        },
        MuiDivider: {
            styleOverrides: {
                root: ({ theme: _theme }) => ({
                    borderColor: _theme.palette.divider, // Asegura que use el color de divider del tema
                })
            }
        }
    },
});

export default theme;