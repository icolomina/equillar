import axios from 'axios';
import { useContext } from 'react';
import { BackendContext } from '../context/BackendContext';

export const useAuth = () => {
  
    const ctx = useContext(BackendContext);

    const login = async (username: string, password: string) => {
      return axios.post(ctx.webserverEndpoint + '/do-login', { username: username, password: password }, {
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

    const getUserData = () => {
      return {
        name: localStorage.getItem('name'),
        role_type: localStorage.getItem('role_type')
      }
    }
  
    return {
      login,
      logout,
      isAuthenticathed,
      isAdmin,
      isCompany,
      getUserData
    }
  };


