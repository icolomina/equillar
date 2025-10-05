#!/bin/sh

# Wait until database is ready
until pg_isready -h database -U equillar; do
  echo "Waiting for DB…"
  sleep 1
done

mv .env.dist .env
echo "✔️  Renamed .env.dist to .env"

mv .env.test.dist .env.test
echo "✔️  Renamed .env.test.dist to .env.test"

rm .env.dev
echo "✔️  Removed .env.dev"


# Generate database schema and load fixtures
php bin/console cache:clear 
php bin/console doctrine:schema:drop --force
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load -n

echo "✔️  Database loaded"

# Create required vault secrets
php bin/console secrets:generate-keys

CRYPT_KEY=$(php -r 'echo bin2hex(sodium_crypto_secretbox_keygen());')
SECURITY_TOKEN_KEY=$(openssl rand -hex 32)
URI_SIGNER_KEY=$(openssl rand -hex 32)

echo -n "$CRYPT_KEY" | php bin/console secrets:set CRYPT_KEY -
echo -n "$SECURITY_TOKEN_KEY" | php bin/console secrets:set SECURITY_TOKEN_KEY -
echo -n "$URI_SIGNER_KEY" | php bin/console secrets:set URI_SIGNER_KEY -

echo "✔️  Required keys generated"

# Generate an Stellar KeyPair for the System
php bin/console app:generate-system-wallet --blockchain="stellar" --network="testnet"
echo "✔️  Custodial keys created"

# Create trustlines for EURC and USDC
php bin/console app:system-address:create-token-trustline --token="USDC"
echo "✔️  Trustline for USDC created"

php bin/console app:system-address:create-token-trustline --token="EURC"
echo "✔️  Trustline for EURC created"

# Deploy the contract wasm (wasm file is located in %kernel.root_dir%/wasm)
php bin/console app:contract:deploy --vers="1.0" --status=STABLE --comments="Local contract version"
echo "✔️  Contract deployed"

# Start the server
exec "$@"