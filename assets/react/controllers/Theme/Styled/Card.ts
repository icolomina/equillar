/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
import { Card, styled } from "@mui/material";

export const ProjectForInvesting = styled(Card)(({ theme }) => ({
  height: '100%',
  display: 'flex',
  flexDirection: 'column',
  borderRadius: theme.shape.borderRadius * 2,
  boxShadow: '0px 5px 15px rgba(0,0,0,0.1)',
  transition: 'transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out',
  '&:hover': {
    transform: 'scale(1.02)',
    boxShadow: '0px 10px 25px rgba(0,0,0,0.15)',
  },

  // anidamos estilos para los hijos si lo deseas:
  '& .MuiCardMedia-root': {
    height: 120,
    borderTopLeftRadius: theme.shape.borderRadius * 2,
    borderTopRightRadius: theme.shape.borderRadius * 2,
  },
  '& .MuiCardContent-root': {
    flexGrow: 1,
    padding: theme.spacing(3),
  },
  '& .paramsContainer': {
    display: 'grid',
    gridTemplateColumns: '1fr 1fr',
    gap: theme.spacing(0.3),
    marginTop: theme.spacing(0.8),
  },
  '& .paramLabel': {
    fontFamily: `'Roboto Mono', monospace`,
    fontSize: '0.7rem',
    color: theme.palette.text.primary,
  },
  '& .paramValue': {
    fontFamily: `'Roboto Mono', monospace`,
    fontSize: '0.7rem',
    fontWeight: 700,
    color: theme.palette.primary.main,
  },
  '& .MuiDivider-root': {
    margin: theme.spacing(2, 0),
  },
  '& .MuiCardActions-root': {
    padding: 0,
  },
  '& .MuiButton-root': {
    padding: theme.spacing(2),
    borderTopLeftRadius: 0,
    borderTopRightRadius: 0,
    borderBottomLeftRadius: theme.shape.borderRadius * 2,
    borderBottomRightRadius: theme.shape.borderRadius * 2,
    '&:hover': {
      backgroundColor: theme.palette.primary.dark,
    },
  },
}));