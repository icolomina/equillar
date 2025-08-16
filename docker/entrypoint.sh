#!/bin/sh

# Wait untl database is ready
until pg_isready -h database -U equillar; do
  echo "Esperando DB…"
  sleep 1
done

if [ -f .env ]; then
  mv .env.dist .env.local
  echo "✔️  Renamed .env.dist to .env.local"
else
  mv .env.dist .env
  echo "✔️  Renamed .env.dist to .env.local"
fi


if [ -f .env.test ]; then
  mv .env.test.dist .env.test.local
  echo "✔️  Renamed .env.test.dist to .env.test.local"
else
  mv .env.test.dist .env.test
  echo "✔️  Renamed .env.test.dist to .env.test"
fi


# Generate database schema and load fixtures
php bin/console cache:clear 
php bin/console doctrine:schema:drop --force
php bin/console doctrine:schema:create -n
php bin/console doctrine:fixtures:load -n

echo "✔️  Database loaded"

# Create required vault secrets
php bin/console secrets:generate-keys

APP_SECRET=$(openssl rand -base64 48)
JWT_KEY=$(openssl rand -base64 48)
URI_SIGNER_KEY=$(openssl rand -base64 48)

echo -n "$APP_SECRET" | php bin/console secrets:set APP_SECRET -
echo -n "$JWT_KEY" | php bin/console secrets:set JWT_KEY -
echo -n "$URI_SIGNER_KEY" | php bin/console secrets:set URI_SIGNER_KEY -

echo "✔️  Required keys generated"

# Generate an Stellar KeyPair for the System
php bin/console app:generate-system-wallet --blockchain="stellar" --network="testnet"
echo "✔️  Custodial keys created"

# Deploy the contract wasm (wasm file is located in %kernel.root_dir/wasm%)
php bin/console app:contract:deploy --vers="1.0" --status=STABLE --comments="Local contract version"
echo "✔️  Contract deployed"

# Start the server
exec "$@"