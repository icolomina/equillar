// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { useQuery } from "@tanstack/react-query";
import { useApi } from "../../hooks/ApiHook";
import { useApiRoutes } from "../../hooks/ApiRoutesHook";
import axios, { AxiosError, AxiosResponse } from "axios";
import { UserPortfolio } from "../../model/user";
import { Fragment } from "react/jsx-runtime";
import { Backdrop, Box, Button, Card, CardContent, CardHeader, CircularProgress, Divider, Grid2, Typography } from "@mui/material";
import { useNavigate } from "react-router-dom";

import UserPortfolioResumeData from "./UserPortfolioResumeData";
import UserPortfolioList from "./UserPortfolioList";

export default function UserPortfolio() {
    const apiRoutes = useApiRoutes();

    const cardStyle = {
        boxShadow: '0 2px 4px rgba(0,0,0,0.1)', 
        borderRadius: '4px', 
        height: '100%', 
    };

    const headerStyle = {
        backgroundColor: '#f0f8ff', 
        padding: '12px',
        display: 'flex',
        justifyContent: 'space-between', 
        alignItems: 'center', 
    };

    const contentStyle = {
        padding: '16px',
    };

    const { callGet } = useApi();
    const navigate = useNavigate();

    const query = useQuery(
        {
            queryKey: ['user-portfolio'],
            queryFn: async () => {
                const result: AxiosResponse<UserPortfolio> | AxiosError = await callGet<object, UserPortfolio>(apiRoutes.getUserPortfolio, {});
                if (!axios.isAxiosError(result)) {
                    return result.data;
                }

                throw new Error(result.message);
            }
        }
    );

    const handleReviewProjectsToInvest = () => {
        return navigate('/app/home-investor');
    };

    if (query.isLoading) {
        return (
            <Fragment>
                <Backdrop
                    sx={(theme) => ({ color: '#fff', zIndex: theme.zIndex.drawer + 1 })}
                    open={query.isLoading}
                //onClick={handleClose}
                >
                    <CircularProgress color="inherit" />
                </Backdrop>
            </Fragment>
        );
    }
    else {
        if (query.isFetched && query.data.isEmpty) {
            return (
                <Fragment>
                    <Box sx={{ p: 2, mt: 2, display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center', height: '100%' }} >
                        <Typography variant="h6" sx={{ mb: 2 }}>
                            Your portfolio is empty
                        </Typography>
                        <Button variant="contained" color="primary" onClick={() => handleReviewProjectsToInvest()}>
                            List Available projects to invest
                        </Button>
                    </Box>

                </Fragment>
            );
        }
    }

    return (
        <Fragment>
            <Box sx={{ flexGrow: 1, p: 2 }}>
                <Typography variant="h4" sx={{ mb: 2 }}>
                    Your portfolio
                </Typography>

                <Card sx={cardStyle}>
                    <CardHeader title="Porfolio resume" sx={headerStyle} />
                    <CardContent sx={contentStyle}>
                        <Grid2 container spacing={3}>
                            <UserPortfolioResumeData title="Deposits" resumeInfo={query.data.resume.depositInfo} ></UserPortfolioResumeData>
                            <UserPortfolioResumeData title="Earnings" resumeInfo={query.data.resume.interestsInfo} ></UserPortfolioResumeData>
                            <UserPortfolioResumeData title="Total" resumeInfo={query.data.resume.totalInfo} ></UserPortfolioResumeData>
                            <UserPortfolioResumeData title="Total paid" resumeInfo={query.data.resume.totalChargedInfo} ></UserPortfolioResumeData>
                            <UserPortfolioResumeData title="Total pending to pay" resumeInfo={query.data.resume.totalPendingToChargeInfo} ></UserPortfolioResumeData>
                            <UserPortfolioResumeData title="Total claimable" resumeInfo={query.data.resume.totalClaimableInfo} ></UserPortfolioResumeData>
                        </Grid2>
                    </CardContent>
                </Card>

                <Divider sx={{ my: 3 }} />

                <Box sx={{ width: '100%' }}>
                    <UserPortfolioList ucl={query.data.userContracts} ></UserPortfolioList>
                </Box>
            </Box>
        </Fragment>
    );

}