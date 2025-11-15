// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { ContractStatus } from "../../../model/contract";

export const getStatusColor = (status: string): string => {
    switch (status) {
      case ContractStatus.APPROVED:
      case ContractStatus.ACTIVE:
        return 'green';
      case ContractStatus.REJECTED:
        return 'red';
      case ContractStatus.REVIEWING:
        return 'orange';
      default:
        return 'black'; 
    }
  }