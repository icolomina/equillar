## Introduction

First of all, many thanks for thinking about contributing. Plaase, read this document carefully for starting with your contribution.

## Prerequisites

Before you begin, make sure you have the following tools installed:

| Tool            | Minimum Version          | Description                                                                  |
|-----------------|-----------------         |------------------------------------------------------------------------------|
| **PHP**         | **8.3 or higher**        | PHP interpreter                                                              |
| **Composer**    | **2.8 or higher**        | Dependency manager for PHP                                                   |
| **Symfony CLI** | **5 or higher**          | Official Symfony command-line tool. User for starting the local webserver    |
| **Node.js**     | **22 or higher**         | For managing frontend assets and build tools                                 |
| **Git**         | **2.4 or higher**        | Version control                                                              |
| **Docker**      | **28 or higher**         | To start the PostgreSQL database                                             |

---

## Project Setup

Follow these steps to clone the repository, configure your environment, and run the application locally.

### 1. Clone the repository

```bash
git clone https://github.com/icolomina/equillar.git
cd equillar
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure environment

```bash
cp .env.dist .env
cp .env.test.dist .env.test
```

The .env.dist file comes with a default password for the local database. After moving the dist files to your enviroment files, if you want to change the local database password, edit the **.env** file and modify the POSTGRES_USER and POSTGRES_PASSWORD with the values of your choice.

After doing it, change the "DATABASE_URL" enviroment var in the ".env" file by this one:

```bash
DATABASE_URL="postgresql://${POSTGRES_USER}:${POSTGRES_PASSWORD}@127.0.0.1:5972/equillar?serverVersion=16&charset=utf8"
```

> If you want to use a diferent port, just edit the "docker-compose.yaml" file and link another port on the database service.

### 4. Generate secrets

This application uses Symfony Vaults to keep and mantain some environment variables as secrets. To generate it follow the next steps:

#### 4.1 Generate the encryption keys
```bash
php bin/console secrets:generate-keys
```

#### 4.2 Generate three random secure values and set them as Symfony secrets

```bash
CRYPT_KEY=$(php -r 'echo bin2hex(sodium_crypto_secretbox_keygen());')
SECURITY_TOKEN_KEY=$(openssl rand -hex 32)
URI_SIGNER_KEY=$(openssl rand -hex 32)

echo -n "$CRYPT_KEY" | php bin/console secrets:set CRYPT_KEY -
echo -n "$SECURITY_TOKEN_KEY" | php bin/console secrets:set SECURITY_TOKEN_KEY -
echo -n "$URI_SIGNER_KEY" | php bin/console secrets:set URI_SIGNER_KEY -
```

### 5. Create the database schema and load fixtures

First of all you have to start the database. The database configuration is defined in the **docker-compose.yaml** file so to start the database you only have to execute the following commands:

```bash
docker create network soroban-equillar
docker compose up -d database
```
And now you are ready to set up the database schema and execute fixtures.

```bash

php bin/console cache:clear 
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load
```

> You do not need to execute migrations if you are going to work in a local environment but, if you create a new entity or add some property to an existing one, please generate the corresponding migration using the following command:

```bash
php bin/console doctrine:migrations:diff 
```

After loading the database, you have to generate an Stellar address so that the platform can deploy the contracts to the blockchain and call their functions later. To do that, execute the following commands:

```bash
php bin/console app:generate-system-wallet --blockchain="stellar" --network="testnet"
php bin/console app:system-address:create-token-trustline --token=USDC
php bin/console app:system-address:create-token-trustline --token=EURC
php bin/console app:contract:deploy --vers="1.0" --status=STABLE --comments="Local contract version"
```

Let's explain the commands line by line:

1. Generate the Stellar Address by which the backend will interact wit Soroban.
2. Creates a trustline within the System Address and the USDC Stellar Asset.
3. Creates a trustline within the System Address and the EURC Stellar Asset.
4. Install the contract code in the Stellar testnet and providers a Wasm ID which will be used to deploy the contracts by the app.

### 6. Execute PhpStan and tests

```bash
vendor/bin/phpstan analyse -l 5 src
vendor/bin/phpunit
```

### 7. Generate frontend

```bash
npm install
npm run watch
```

> "npm run watch" rebuild the assets after every change in the frontend. If you do not need this feature, execute "npm run dev" instead.

### 8. Start the development server

```bash
symfony server:start --no-tls
```

The application will be available at http://127.0.0.1:8000 by default.


## Making a contribution

Thank you for wanting to contribute. Below are the guidelines and expected workflow to make your changes useful and easy to integrate.

If you ae going to create an issue for your contribution, try to include the following information please:

- A clear, descriptive title.
- Environment details (PHP, Composer, Symfony CLI, Node, OS).
- Steps to reproduce the problem.
- Expected vs actual behavior.
- Any relevant logs, stack traces, or screenshots.
- A minimal reproducible example if possible.

### 1. How to start

- Fork the repository and work from your fork.
- Create a branch from main with a clear, related name:
    - For issues: _short-description (e.g. 42_fix-login-bug).
    - For features: feature/ (e.g. feature/session-timeout).
    - For hotfixes: hotfix/.

### 2. Commit style

- Use clear commit messages and prefer English. Recommended format:
   - Short first line (≤72 characters) summarizing the change.
   - Blank line.
   - Optional description explaining why, what changed, and any additional considerations.

- Prefer small, atomic commits; group logically related changes.

### 3. Tests and quality

- Run the test suite before opening the PR:
    - phpunit: vendor/bin/phpunit
    - phpstan: vendor/bin/phpstan analyse -l 5 src
- Add unit/integration tests for any new behavior or bugfix.
- Ensure nothing breaks in CI, which includes linting, static analysis and tests.
- Do not commit secrets, credentials, or private keys. Use Symfony secrets for sensitive configuration.

### 4. Dependencies

- If you add or update dependencies, document the reason in the PR and ensure versions are compatible with composer.json / package.json.
- Do not include unnecessary lockfile changes; update lockfiles only if your change requires it.

### 5. Make a Pull Request

-  Push your branch to your fork:
```bash
git push origin your-branch-name
```
- Open a Pull Request (PR) against the main branch of the upstream repository:
    
    - Use a descriptive title
    - In the PR description include:
        
        - What the change does (If the PR is not relatd to an issue).
        - Why it is needed or why it can be useful (If the PR is not relatd to an issue) .
        - Any important implementation details
        - How to test the change locally
    - Add relevant labels, milestone, and assignees when possible.
    - Reference any related issue by number (e.g. closes "#123") so it is linked automatically.

- Update documentation or README entries for any feature or behavior changes that affect users or developers.
- Reviewers will focus on correctness, readability, tests, and security implications.
- Respond to review comments promptly and update the PR with requested changes.
- Keep discussions civil and focused on the code; use PR threads for technical decisions.