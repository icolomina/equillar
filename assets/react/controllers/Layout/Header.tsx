// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import * as React from 'react';
import { styled } from '@mui/material/styles';
import AppBar from '@mui/material/AppBar';
import Box from '@mui/material/Box';
import Toolbar from '@mui/material/Toolbar';
import IconButton from '@mui/material/IconButton';
import Typography from '@mui/material/Typography';
import Badge from '@mui/material/Badge';
import MenuItem from '@mui/material/MenuItem';
import Menu from '@mui/material/Menu';
import AccountCircle from '@mui/icons-material/AccountCircle';
import MailIcon from '@mui/icons-material/Mail';
import NotificationsIcon from '@mui/icons-material/Notifications';
import MoreIcon from '@mui/icons-material/MoreVert';
import { Avatar, Divider, Tooltip } from '@mui/material';
import MonetizationOnIcon from '@mui/icons-material/MonetizationOn';
import { useAuth } from '../../hooks/AuthHook';


const ProjectTitle = styled(Typography)(({ theme }) => ({
  variant: 'h5', // Increased font size
  fontWeight: 700, // Made it bolder
  fontFamily: "'Montserrat', sans-serif", // Using a more modern and attractive font
  letterSpacing: '0.5px', // Slight letter spacing for better readability
  color: theme.palette.primary.main, // Using the primary color for emphasis (you might need to define this in your theme)
  [theme.breakpoints.down('sm')]: {
    fontSize: '1.2rem', // Adjust font size for smaller screens
  },
}));

export default function Header() {
  const [anchorEl, setAnchorEl] = React.useState<null | HTMLElement>(null);
  const [mobileMoreAnchorEl, setMobileMoreAnchorEl] = React.useState<null | HTMLElement>(null);

  const isMenuOpen = Boolean(anchorEl);
  const isMobileMenuOpen = Boolean(mobileMoreAnchorEl);

  const {getUserData} = useAuth();

  const handleProfileMenuOpen = (event: React.MouseEvent<HTMLElement>) => {
    setAnchorEl(event.currentTarget);
  };

  const handleMobileMenuClose = () => {
    setMobileMoreAnchorEl(null);
  };

  const handleMenuClose = () => {
    setAnchorEl(null);
    handleMobileMenuClose();
  };

  const handleMobileMenuOpen = (event: React.MouseEvent<HTMLElement>) => {
    setMobileMoreAnchorEl(event.currentTarget);
  };

  const menuId = 'primary-search-account-menu';
  const renderMenu = (
    <Menu
      anchorEl={anchorEl}
      anchorOrigin={{
        vertical: 'top',
        horizontal: 'right',
      }}
      id={menuId}
      keepMounted
      transformOrigin={{
        vertical: 'top',
        horizontal: 'right',
      }}
      open={isMenuOpen}
      onClose={handleMenuClose}
    >
      <MenuItem onClick={handleMenuClose}>Profile</MenuItem>
      <MenuItem onClick={handleMenuClose}>My account</MenuItem>
    </Menu>
  );

  const mobileMenuId = 'primary-search-account-menu-mobile';
  const renderMobileMenu = (
    <Menu
      anchorEl={mobileMoreAnchorEl}
      anchorOrigin={{
        vertical: 'top',
        horizontal: 'right',
      }}
      id={mobileMenuId}
      keepMounted
      transformOrigin={{
        vertical: 'top',
        horizontal: 'right',
      }}
      open={isMobileMenuOpen}
      onClose={handleMobileMenuClose}
    >
      <MenuItem>
        <IconButton size="large" aria-label="show 4 new mails" color="inherit">
          <Badge badgeContent={4} color="error">
            <MailIcon />
          </Badge>
        </IconButton>
        <p>Messages</p>
      </MenuItem>
      <MenuItem>
        <IconButton
          size="large"
          aria-label="show 17 new notifications"
          color="inherit"
        >
          <Badge badgeContent={17} color="error">
            <NotificationsIcon />
          </Badge>
        </IconButton>
        <p>Notifications</p>
      </MenuItem>
      {/* Se ha modificado para mostrar el nombre y el rol en el mismo MenuItem del perfil */}
      <MenuItem onClick={handleProfileMenuOpen}>
        <IconButton
          size="large"
          aria-label="account of current user"
          aria-controls="primary-search-account-menu"
          aria-haspopup="true"
          color="inherit"
        >
          <AccountCircle />
        </IconButton>
        <Box sx={{ display: 'flex', flexDirection: 'column', ml: 1 }}>
          <Typography variant="body2" color="text.secondary">
              {getUserData().name}
          </Typography>
          {getUserData().organization.length > 0 ? (
            <Typography variant="body2" color="text.secondary">
              {getUserData().organization} - {getUserData().role_type}
            </Typography>
          ) : (
            <Typography variant="body2" color="text.secondary">
              {getUserData().role_type}
            </Typography>
          )}
        </Box>
      </MenuItem>
    </Menu>
  );

  return (
    <Box sx={{ flexGrow: 1 }}>
      <AppBar position="static" sx={{ backgroundColor: '#fff', color: '#333', boxShadow: '0 1px 3px rgba(0, 0, 0, 0.12)' }}>
        <Toolbar>
          <IconButton
            size="large"
            edge="start"
            color="inherit"
            aria-label="App Logo"
            sx={{ mr: 2 }}
          >
            <MonetizationOnIcon />
          </IconButton>
          <ProjectTitle
            noWrap
            sx={{ display: { xs: 'block', sm: 'block' } }}
          >
            Equillar
          </ProjectTitle>
          <Box sx={{ flexGrow: 1 }} />
          <Box sx={{ display: { xs: 'none', md: 'flex' }, alignItems: 'center' }}>
            <Divider orientation="vertical" flexItem sx={{ mx: 4 }} />
            {/* Se muestran los íconos con badge en la versión de escritorio */}
            <IconButton size="large" aria-label="show 4 new mails" color="inherit">
              <Badge badgeContent={0} color="error">
                <MailIcon />
              </Badge>
            </IconButton>
            <IconButton
              size="large"
              aria-label="show 17 new notifications"
              color="inherit"
            >
              <Badge badgeContent={0} color="error">
                <NotificationsIcon />
              </Badge>
            </IconButton>
            <Divider orientation="vertical" flexItem sx={{ mx: 4 }} />
            {/* Se añade la información del usuario junto al icono de perfil */}
            <Tooltip title="Cuenta del usuario">
              <IconButton
                size="large"
                edge="end"
                aria-label="account of current user"
                aria-controls={menuId}
                aria-haspopup="true"
                onClick={handleProfileMenuOpen}
                color="inherit"
                sx={{ ml: 4 }}
              >
                <Avatar sx={{ width: 32, height: 32 }}>
                  <AccountCircle />
                </Avatar>
              </IconButton>
            </Tooltip>
            <Box sx={{ display: 'flex', flexDirection: 'column', ml: 1, mr: 2 }}>
              <Typography variant="body1" fontWeight="bold" sx={{ color: 'text.primary' }}>
                {getUserData().name}
              </Typography>
              {getUserData().organization.length > 0 ? (
                <Typography variant="body2" color="text.secondary">
                  {getUserData().organization} - {getUserData().role_type}
                </Typography>
              ) : (
                <Typography variant="body2" color="text.secondary">
                  {getUserData().role_type}
                </Typography>
              )}
            </Box>
          </Box>
          <Box sx={{ display: { xs: 'flex', md: 'none' } }}>
            <IconButton
              size="large"
              aria-label="show more"
              aria-controls={mobileMenuId}
              aria-haspopup="true"
              onClick={handleMobileMenuOpen}
              color="inherit"
            >
              <MoreIcon />
            </IconButton>
          </Box>
        </Toolbar>
      </AppBar>
      {renderMobileMenu}
      {renderMenu}
    </Box>
  );
}
