/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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