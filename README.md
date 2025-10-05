
[![CI](https://github.com/icolomina/equillar/actions/workflows/ci.yml/badge.svg)](https://github.com/icolomina/equillar/actions/workflows/ci.yml)

[![License: MPL 2.0](https://img.shields.io/badge/License-MPL%202.0-brightgreen.svg)](https://opensource.org/licenses/MPL-2.0)

# Equillar

Equillar is an open-source fintech application that enables companies and institutions to publish projects seeking funding, and allows investors to contribute capital using stablecoins issued on the [Stellar](https://stellar.org/) blockchain using [Soroban](https://stellar.org/soroban) contracts. 
This application deploy the contracts on the [Soroban testnet](https://developers.stellar.org/docs/build/smart-contracts/getting-started/deploy-to-testnet) and those contrats manage balances, payments and returns.

Equillar is not offered as a deployed SaaS service. It provides a technology stack that organizations and developers can use as the foundation for their own applications.

![Equillar capital contributon](docs/images/equillar_short_capital_contribution_optimized.gif)

# License

This project is licensed under the Mozilla Public License 2.0 (MPL‑2.0). See the [LICENSE](./LICENSE) file for details.

# Value proposition

Equillar provides a complete open-source fintech platform for collaborative investment, designed for companies and institutions seeking funding, and for investors who want to contribute capital using stablecoins on the Stellar blockchain via Soroban smart contracts.

## Key features and benefits

- **End-to-end application:** Includes both backend (Symfony + PHP) and frontend (React + Typescript) codebases, ready to install and test.
- **Soroban smart contract integration:** Investment logic is managed by Soroban contracts deployed on the Stellar Testnet. The contract code is also hosted on Github with clear instructions for installing and testing it. 
- **Modern technology stack:** Uses PostgreSQL, Symfony UX, Webpack Encore, Material UI, and modular React architecture for scalability and maintainability.
- **Ready-to-use demo:** Quick local setup with Docker Compose; test the platform and explore its features without code changes.
- **Comprehensive dashboard:** Manage projects, investments, and returns with a user-friendly interface. Includes company and investor flows, project creation, and capital management.
- **Freigther wallet support:** Tested with Freigther wallet for Soroban contract interactions.
- **Clear folder structure:** Well-documented backend and frontend organization for easy navigation and extension.
- **Extensible and customizable:** Fork, modify, and extend both the application and smart contracts to fit your business needs.
- **Community-driven:** Open to contributions, with guidelines and support for developers.

Whether you want to run a demo, integrate with your systems, or build on top of Equillar, this project gives you a robust foundation for blockchain-based investment management.

# Table of Contents

- [Installation](docs/installation.md)
- [Soroban Contract](docs/contract.md)
- [Freigther Wallet](docs/freigther-wallet.md)
- [Exploring the Dashboard](docs/exploring_the_dashboard.md)
- [Tech Stack](docs/tech-stack.md)
- [Backend Folder Structure](docs/backend-folder-structure.md)
- [Frontend Folder Structure](docs/frontend-folder-structure.md)

# How to contribute

We welcome contributions from the community. Please read our [CONTRIBUTING guidelines](CONTRIBUTING.md) before submitting issues or pull requests.

# Contact

For questions or support, you can open an issue or create a new [discussion](https://github.com/icolomina/equillar/discussions)