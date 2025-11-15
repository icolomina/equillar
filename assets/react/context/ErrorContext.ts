// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { createContext } from "react";
import { BlockchainError } from "../model/error";

export interface ErrorContextValue {
    error: BlockchainError | null;
    showError: (error: BlockchainError) => void;
    clearError: () => void;
}

export const ErrorContext = createContext<ErrorContextValue | null>(null);
