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