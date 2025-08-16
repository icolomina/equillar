export interface StellarTransaction {
    isSuccessful: boolean,
    ledger: number,
    feeCharged: string,
    hash: string
}