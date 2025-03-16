import { useEffect, useState } from "react"

export interface ReloadedRoute {
    getRouteToNavigate: () => string,
    removeRouteToNavigate: () => void
}

export const useReloadedRoute = (path?: string) => {

    if(path){
        console.log('metemos la ruta' + path);
        localStorage.setItem('route_to_redirect', path);
    }

    const getRouteToNavigate = () => {
        return localStorage.getItem('route_to_redirect');
    }

    const removeRouteToNavigate = () => {
        localStorage.removeItem('route_to_redirect');
    }

    return {
        getRouteToNavigate,
        removeRouteToNavigate
    };
}