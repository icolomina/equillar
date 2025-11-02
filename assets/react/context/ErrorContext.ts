/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { createContext } from "react";
import { BlockchainError } from "../model/error";

export interface ErrorContextValue {
    error: BlockchainError | null;
    showError: (error: BlockchainError) => void;
    clearError: () => void;
}

export const ErrorContext = createContext<ErrorContextValue | null>(null);
