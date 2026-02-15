// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { useQuery } from "@tanstack/react-query";
import { useParams } from "react-router-dom";
import { ContractOutput, getReturnType } from "../../model/contract";
import axios, { AxiosError, AxiosResponse } from "axios";
import { useApi } from "../../hooks/ApiHook";
import { useApiRoutes } from "../../hooks/ApiRoutesHook";
import ContractForm, { FormValues } from "./Form/ContractForm";
import { Fragment } from "react/jsx-runtime";
import { Backdrop, CircularProgress } from "@mui/material";

export default function EditContract() {
    const params = useParams();
    const { callGet } = useApi();
    const apiRoutes = useApiRoutes();


    const query = useQuery<ContractOutput>(
        {
            queryKey: ['edit-contract', params.id],
            queryFn: async () => {
                const result: AxiosResponse<ContractOutput> | AxiosError = await callGet<object, ContractOutput>(apiRoutes.editContract(params.id), {});
                if (!axios.isAxiosError(result)) {
                    return result.data;
                }

                throw new Error(result.message);
            },
            retry: 0
        }
    );

    if(query.isLoading) {
        return (
            <Fragment>
                <Backdrop
                    sx={(theme) => ({ color: "#fff", zIndex: theme.zIndex.drawer + 1 })}
                    open={query.isLoading}
                //onClick={handleClose}
                >
                    <CircularProgress color="inherit" />
                </Backdrop>
            </Fragment>
        );
    }
    else{
        const formValues: FormValues = {
            ...query.data,
            token: query.data.tokenContract.code,
            rate: String(query.data.rate),
            returnType: getReturnType(query.data.returnType)
        }
        return (
            <ContractForm contract={formValues}></ContractForm>
        )
    }
}