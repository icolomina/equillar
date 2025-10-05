# Soroban Contract

This document explains how the Soroban smart contract is integrated into this project and how to work with it.

## Contract Location in Project

The compiled Soroban contract is stored as a WebAssembly (WASM) file in this project:

```
wasm/
└── investment.wasm
```

This WASM file contains the compiled smart contract that handles the investment logic for the platform.

## Contract Source Code

The source code for the Soroban contract can be found in the dedicated contracts repository:

**Repository:** [soroban-contracts](https://github.com/icolomina/soroban-contracts)  
**Contract Path:** [investment contract](https://github.com/icolomina/soroban-contracts/tree/main/investment)

## Updating the Contract

If you need to modify the contract code, follow these steps:

### 1. Fork and Modify the Contract

1. Fork the [soroban-contracts repository](https://github.com/icolomina/soroban-contracts)
2. Make your modifications to the investment contract code
3. Follow the compilation instructions in the [soroban-contracts documentation](https://github.com/icolomina/soroban-contracts) to generate the new WASM file

### 2. Update the WASM File

Once you have generated the new WASM file from your modified contract:

1. Replace the existing `wasm/investment.wasm` file in this project with your newly compiled WASM file
2. Ensure the new WASM file maintains the same filename: `investment.wasm`
3. Test your application to verify the contract changes work as expected

## Additional Resources

For detailed information about:
- Contract compilation
- Development setup
- Contract testing
- API documentation

Please refer to the main [soroban-contracts repository documentation](https://github.com/icolomina/soroban-contracts).