// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { ReactNode, useState } from "react";
import { ErrorContext } from "./ErrorContext";
import { BlockchainError } from "../model/error";
import BlockchainErrorModal from "../components/BlockchainErrorModal";

interface ErrorProviderProps {
    children: ReactNode;
}

export const ErrorProvider = ({ children }: ErrorProviderProps) => {
    const [error, setError] = useState<BlockchainError | null>(null);
    const [open, setOpen] = useState(false);

    const showError = (error: BlockchainError) => {
        setError(error);
        setOpen(true);
    };

    const clearError = () => {
        setOpen(false);
        // Wait for modal animation to complete before clearing error
        setTimeout(() => setError(null), 300);
    };

    return (
        <ErrorContext.Provider value={{ error, showError, clearError }}>
            {children}
            <BlockchainErrorModal 
                open={open} 
                error={error} 
                onClose={clearError} 
            />
        </ErrorContext.Provider>
    );
};
