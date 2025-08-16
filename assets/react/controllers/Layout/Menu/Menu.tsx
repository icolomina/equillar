
import { Divider, ListItemIcon, ListItemText, MenuItem, MenuList, Typography, useTheme } from "@mui/material";
import { Link as RouterLink, useLocation } from 'react-router-dom';
import * as React from "react";
import LogoutIcon from '@mui/icons-material/Logout'
import { MenuItem as VerticalMenuItem } from "./MenuItems";

interface MenuProps {
    handleLogout: (event: any) => void; 
    items: VerticalMenuItem[];
}


export default function Menu(props: MenuProps) {

  const location = useLocation();
  const theme = useTheme(); 

  const isActive = (path: string) => location.pathname === path;

  const menuItemStyle = (path?: string) => {
    const active = path ? isActive(path) : false;
    return {
      borderRadius: '8px', 
      marginBottom: theme.spacing(0.5), 
      paddingLeft: theme.spacing(2),
      paddingRight: theme.spacing(2),
      '&:hover': {
        backgroundColor: active ? theme.palette.primary.dark : theme.palette.action.hover, 
      },
      ...(active && {
        backgroundColor: theme.palette.primary.main,
        color: theme.palette.primary.contrastText,
        '& .MuiListItemIcon-root': {
          color: theme.palette.primary.contrastText,
        },
      }),
      ...(!active && {
        color: theme.palette.text.secondary, 
         '& .MuiListItemIcon-root': {
          color: theme.palette.action.active, 
        },
      }),
    };
  };

  const menuSectionTitle = "Menu"; 

  return (
    <React.Fragment>
      <Typography
        variant="overline"
        component="div"    
        sx={{
          display: 'block',
          paddingLeft: theme.spacing(3),
          paddingRight: theme.spacing(1), 
          paddingTop: theme.spacing(0.5),
          paddingBottom: theme.spacing(1.5), 
          color: theme.palette.text.secondary,
          lineHeight: 'normal', 
          fontWeight: 'bold'
        }}
      >
        {menuSectionTitle}
      </Typography>

      <MenuList sx={{ padding: theme.spacing(1) }}> {/* Padding general para el MenuList */}
        {props.items.map((item) => (
          <MenuItem
            key={item.text}
            component={RouterLink}
            to={item.path}
            sx={menuItemStyle(item.path)}
          >
            <ListItemIcon sx={{ minWidth: 'auto', marginRight: theme.spacing(1.5) }}>
              {React.cloneElement(item.icon, { fontSize: 'small' })}
            </ListItemIcon>
            <ListItemText
              primary={item.text}
              slotProps={{ primary: {
                variant: 'body1', 
                fontWeight: isActive(item.path) ? 'medium' : 'regular',
              }}}
            />
          </MenuItem>
        ))}
        <Divider sx={{ marginY: theme.spacing(1) }} />
        <MenuItem
          onClick={props.handleLogout}
          sx={{
            ...menuItemStyle(), 
            color: theme.palette.text.secondary,
            '& .MuiListItemIcon-root': {
              color: theme.palette.action.active,
            },
            '&:hover': { 
              backgroundColor: theme.palette.action.hover,
            },
          }}
        >
          <ListItemIcon sx={{ minWidth: 'auto', marginRight: theme.spacing(1.5) }}>
            <LogoutIcon fontSize="small" />
            {/* O <LogoutIcon fontSize="small" /> */}
          </ListItemIcon>
          <ListItemText
            primary="Logout"
            slotProps={{ primary: {variant: 'body2'} }}
          />
        </MenuItem>
      </MenuList>
    </React.Fragment>
  );
}
