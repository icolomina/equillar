import { useState } from 'react';
import axios, { AxiosError, AxiosResponse } from 'axios';

export const useAuth = () => {
  
    const login = async (username: string, password: string, endpoint: string) => {
      return axios.post('https://127.0.0.1:8000/do-login', { username: username, password: password }, {
        headers: {
          'Content-Type': 'application/json'
        }
      });
    };
  
    const logout = () => {
      localStorage.removeItem('token'); 
    };

    const isAuthenticathed = () => {
      return localStorage.getItem('token');
    }

    const isAdmin = () => {
      const role = localStorage.getItem('role');
      return role === 'ROLE_ADMIN';
    }

    const isCompany = () => {
      const role = localStorage.getItem('role');
      return role === 'ROLE_COMPANY' ;
    }
  
    return {
      login,
      logout,
      isAuthenticathed,
      isAdmin,
      isCompany
    }
  };


