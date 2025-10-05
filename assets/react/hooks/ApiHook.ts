/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
import axios, { AxiosError, AxiosResponse } from "axios";
import { useContext, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { sprintf } from "sprintf-js";
import { BackendContext } from "../context/BackendContext";

type Headers = {
    [key: string]: string|number
}

enum HttpMethod {
    GET = 'GET',
    POST = 'POST',
    PUT = 'PUT',
    DELETE = 'DELETE',
    PATCH = 'PATCH'
};

function prepareHeaders(httpMethod: HttpMethod, isFile: boolean): Headers {
    const token = localStorage.getItem('token');
    const headers: Headers = {
        'Authorization' : 'Bearer ' + token
    };

    if(httpMethod == HttpMethod.POST || httpMethod == HttpMethod.PATCH) {
        (!isFile) 
            ? headers['Content-Type'] = 'application/json'
            : headers['Content-Type'] = 'multipart/form-data'
        ;
    }

    return headers;
}

export const useApi = () => {

    const navigate = useNavigate();
    const ctx = useContext(BackendContext);

    useEffect(() => {
        const responseInterceptor = axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response && error.response.status === 401) {
                    navigate('/login');
                }
                return Promise.reject(error);
            }
        );

        return () => {
            axios.interceptors.response.eject(responseInterceptor);
        };
    }, [navigate]);

    const callGetDownloadFile = <T extends object, R>(path: string, queryParams: T, extraHeaders: object = {}): Promise<AxiosResponse<R>|AxiosError> => {
        let headers: Headers = prepareHeaders(HttpMethod.GET, false);
        if(Object.keys(extraHeaders).length > 0) {
            headers = { ...headers, ...extraHeaders as Headers };
        }
        return axios.get(ctx.webserverEndpoint + path, {
            params: queryParams,
            headers: headers,
            responseType: 'blob'
        });
    }

    const callGet = <T extends object, R>(path: string, queryParams: T, extraHeaders: object = {}): Promise<AxiosResponse<R>|AxiosError> => {
        let headers: Headers = prepareHeaders(HttpMethod.GET, false);
        if(Object.keys(extraHeaders).length > 0) {
            headers = { ...headers, ...extraHeaders as Headers };
        }
        return axios.get(ctx.webserverEndpoint + path, {
            params: queryParams,
            headers: headers
        });
    };

    const callPost = <T extends object, R>(path: string, payload: T, isFile: boolean = false): Promise<AxiosResponse<R>|AxiosError> => {
        const headers: Headers = prepareHeaders(HttpMethod.POST, isFile);
        return axios.post(ctx.webserverEndpoint + path, payload, {
            headers: headers
        });
    };

    const callPatch = <T extends object, R>(path: string, payload: T): Promise<AxiosResponse<R>|AxiosError> => {
        const headers: Headers = prepareHeaders(HttpMethod.PATCH, false);
        return axios.patch(ctx.webserverEndpoint + path, payload, {
            headers: headers
        });
    };

    const buildUrl = (path: string, params: any[]) => {
        return sprintf(path, params);
    }

    return {
        callGet,
        callPost,
        callPatch,
        callGetDownloadFile,
        buildUrl
    };
}