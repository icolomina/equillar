import { useMutation, UseMutationResult, useQuery, UseQueryOptions } from "@tanstack/react-query";
import axios, { AxiosError, AxiosResponse } from "axios";
import { useEffect } from "react";
import { useNavigate } from "react-router-dom";

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

function getBaseUrl(): string {
    return window.location.origin;
}

export const useApi = () => {

    const navigate = useNavigate();

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

    const callGet = <T extends object, R>(path: string, queryParams: T): Promise<AxiosResponse<R>|AxiosError> => {
        const headers: Headers = prepareHeaders(HttpMethod.GET, false);
        return axios.get(getBaseUrl() + path, {
            params: queryParams,
            headers: headers
        });
    };

    const callPost = <T extends object, R>(path: string, payload: T, isFile: boolean = false): Promise<AxiosResponse<R>|AxiosError> => {
        const headers: Headers = prepareHeaders(HttpMethod.POST, isFile);
        return axios.post(getBaseUrl() + path, payload, {
            headers: headers
        });
    };

    const callPatch = <T extends object, R>(path: string, payload: T): Promise<AxiosResponse<R>|AxiosError> => {
        const headers: Headers = prepareHeaders(HttpMethod.PATCH, false);
        return axios.patch(getBaseUrl() + path, payload, {
            headers: headers
        });
    };

    const useGetQuery = <T extends object, R>(path: string, queryParams: T, options?: UseQueryOptions<R, AxiosError>) => {
        return useQuery<R, AxiosError>({
            queryKey: [path, queryParams],
            queryFn: async(): Promise<R> => {
                const response: AxiosResponse<R>|AxiosError = await callGet<T, R>(path, queryParams);
                if(!axios.isAxiosError(response)) {
                    return response.data;
                }

                throw new Error(response.message);
            },
            ... options
        });
    };

    //const usePostMutation = 

    return {
        callGet,
        callPost,
        callPatch,
        useGetQuery
    };
}