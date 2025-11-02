/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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
