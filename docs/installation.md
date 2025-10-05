## App Installation

### For developers

- See the [CONTRIBUTING](../CONTRIBUTING.md) file for a step-by-step setup to run and develop the project locally.
- This guide installs all required tools and dependencies so you can explore, run tests and modify the code.

### Quick demo (local, no code changes)

> - This way assumes you have installed docker and docker compose in your computer.
> - The **.env.dist** file comes with a default password for the local database. If you want to change it, edit the **.env.dist** file and modify the **POSTGRES_USER** and **POSTGRES_PASSWORD** with the values of your choice.

Run the following commands from a folder of your choice

```bash
git clone https://github.com/icolomina/equillar.git
cd equillar
docker network create soroban-equillar
docker compose up
```
> if you are re-creating the containers and want to avoid cache, run the next commands:

```bash
docker-compose build --no-cache
docker-compose up
``` 

After doing that, you will be able to access the platform dashboard following this url: "http://127.0.0.1:8000/app"