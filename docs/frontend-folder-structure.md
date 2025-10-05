## Frontend Structure

The "assets/react" folder contains the main source code for the frontend application, built with React. The organization is modular, with each subfolder serving a specific purpose in the application's architecture.

### The context folder
Holds React Contexts and Providers that manage global state and shared logic across the application. Contexts allow different parts of the app to access shared data and functions.

Examples of contexts:
- **BackendContext.ts**: Provides backend configuration and endpoints to the app.

When adding a new context, name it according to the responsibility it manages and end it with "Context" (e.g., "ThemeContext", "AuthContext").

### The controllers folder
Contains the main React components of the application. Each subfolder or file represents a page, layout, or reusable UI element. Components handle rendering, user interaction, and may use hooks, contexts, and services for data and logic.

Examples of controllers:
- **App.tsx**: Main application component that sets up routing and global providers.
- **ProtectedRoute.tsx**: Component that restricts access to routes based on authentication status.
- **HomeInvestor.tsx**: Component that renders the projects that the investor can invest in.

When adding a new component, name it according to its responsibility or what they list or handle (e.g., "ProjectList.tsx", "InvestorDashboard.tsx"). Place page-level components in their own folders if they have related subcomponents.

### The hooks folder
Includes custom React hooks that encapsulate reusable logic for state management, side effects, and data fetching. Hooks simplify component logic and promote reuse.

Examples of hooks:
- **AuthHook.ts**: Provides authentication logic (login, logout, token management).
- **ContractHook.ts**: Encapsulates logic for interacting with Soroban contracts and investing in projects.

When adding a new hook, suffix the file with the "Hook" word and name the hook function using the word use as a prefix. For instance: (ApiHook -> useApi).

### The model folder
Defines TypeScript interfaces, types, and models used throughout the frontend. Centralizes data structures for consistency and type safety.

Examples of models:
- **contract.ts**: Defines contract-related types and enums (e.g., "ContractStatus", "ContractBalance").
- **token.ts**: Defines token and token contract interfaces.

When adding a new model, name the file and exported types according to the domain concept they represent, using camelCase or PascalCase as appropriate (e.g., "user.ts", "transaction.ts").


### The utils folder
Provides utility functions and helpers used across the frontend. Utilities are stateless and reusable, supporting tasks like formatting, validation, and data transformation.

Examples of utils:
- **token.ts**: Contains helpers for working with token values and formatting.
- **currency.ts**: Provides functions for formatting currency values based on locale and token contract.

When adding a new utility, name the file and functions according to their purpose, using camelCase (e.g., "formatDate", "validateAddress"). Group related utilities in the same file when appropriate.
