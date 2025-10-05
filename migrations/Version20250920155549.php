<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250920155549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE contract_investments_pause_resume (id INT NOT NULL, contract_id INT NOT NULL, contract_transaction_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(25) NOT NULL, current_funds DOUBLE PRECISION NOT NULL, reason TEXT DEFAULT NULL, status VARCHAR(25) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_d99545012576e0fd ON contract_investments_pause_resume (contract_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_d9954501a749ca68 ON contract_investments_pause_resume (contract_transaction_id)');
        $this->addSql('COMMENT ON COLUMN contract_investments_pause_resume.date IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE contract_withdrawal_approval (id INT NOT NULL, contract_withdrawal_request_id INT NOT NULL, contract_transaction_id INT NOT NULL, approved_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status VARCHAR(25) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_e09263843f3fb9d8 ON contract_withdrawal_approval (contract_withdrawal_request_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_e0926384a749ca68 ON contract_withdrawal_approval (contract_transaction_id)');
        $this->addSql('COMMENT ON COLUMN contract_withdrawal_approval.approved_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE user_contract_payment (id INT NOT NULL, user_contract_id INT NOT NULL, transaction_id INT DEFAULT NULL, hash VARCHAR(255) DEFAULT NULL, total_claimed DOUBLE PRECISION DEFAULT NULL, paid_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_186cbb188c6d2968 ON user_contract_payment (user_contract_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_186cbb182fc0cb0f ON user_contract_payment (transaction_id)');
        $this->addSql('COMMENT ON COLUMN user_contract_payment.paid_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_contract_payment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE contract_transaction (id INT NOT NULL, contract_address VARCHAR(255) DEFAULT NULL, contract_label VARCHAR(100) NOT NULL, function_called VARCHAR(50) NOT NULL, trx_result VARCHAR(20) DEFAULT NULL, trx_hash VARCHAR(255) DEFAULT NULL, trx_result_data JSON DEFAULT NULL, trx_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, error TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN contract_transaction.trx_date IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE contract_reserve_fund_contribution (id INT NOT NULL, source_user_id VARCHAR(255) NOT NULL, contract_trasaction_id INT DEFAULT NULL, contract_id INT NOT NULL, uuid VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, status VARCHAR(50) NOT NULL, received_transaction_hash VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, received_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, transferred_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_fb06c29d2576e0fd ON contract_reserve_fund_contribution (contract_id)');
        $this->addSql('CREATE INDEX idx_fb06c29deeb16bfd ON contract_reserve_fund_contribution (source_user_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_fb06c29dcb0c5ddb ON contract_reserve_fund_contribution (contract_trasaction_id)');
        $this->addSql('COMMENT ON COLUMN contract_reserve_fund_contribution.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contract_reserve_fund_contribution.received_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contract_reserve_fund_contribution.transferred_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE contract_balance (id INT NOT NULL, contract_transaction_id INT DEFAULT NULL, contract_id INT NOT NULL, available DOUBLE PRECISION DEFAULT NULL, reserve_fund DOUBLE PRECISION DEFAULT NULL, comission DOUBLE PRECISION DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status VARCHAR(25) NOT NULL, funds_received DOUBLE PRECISION DEFAULT NULL, payments DOUBLE PRECISION DEFAULT NULL, reserve_contributions DOUBLE PRECISION DEFAULT NULL, project_withdrawals DOUBLE PRECISION DEFAULT NULL, available_to_reserve_movements DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_a4a562362576e0fd ON contract_balance (contract_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_a4a56236a749ca68 ON contract_balance (contract_transaction_id)');
        $this->addSql('COMMENT ON COLUMN contract_balance.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contract_balance.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE user_contract (id INT NOT NULL, contract_id INT NOT NULL, usr_id VARCHAR(255) NOT NULL, user_wallet_id INT NOT NULL, balance DOUBLE PRECISION NOT NULL, interests DOUBLE PRECISION DEFAULT NULL, total DOUBLE PRECISION DEFAULT NULL, hash VARCHAR(255) NOT NULL, total_charged DOUBLE PRECISION DEFAULT NULL, last_payment_received_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, claimable_ts INT NOT NULL, status VARCHAR(25) NOT NULL, regular_payment DOUBLE PRECISION DEFAULT NULL, commission DOUBLE PRECISION DEFAULT NULL, real_deposited DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_902cc592576e0fd ON user_contract (contract_id)');
        $this->addSql('CREATE INDEX idx_902cc5971c5ad17 ON user_contract (user_wallet_id)');
        $this->addSql('CREATE INDEX idx_902cc59c69d3fb ON user_contract (usr_id)');
        $this->addSql('COMMENT ON COLUMN user_contract.last_payment_received_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_contract.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE user_wallet (id INT NOT NULL, usr_id VARCHAR(255) NOT NULL, network VARCHAR(25) NOT NULL, address VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_193a8922c69d3fb ON user_wallet (usr_id)');
        $this->addSql('COMMENT ON COLUMN user_wallet.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE blockchain_network (id INT NOT NULL, blockchain_id INT NOT NULL, name VARCHAR(50) NOT NULL, label VARCHAR(50) NOT NULL, url VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, test BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_80740b9898073ae1 ON blockchain_network (blockchain_id)');
        $this->addSql('COMMENT ON COLUMN blockchain_network.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE configuration (id INT NOT NULL, config_key VARCHAR(100) NOT NULL, config_value VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN configuration.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE contract_code (id INT NOT NULL, wasm_id VARCHAR(255) NOT NULL, version VARCHAR(25) NOT NULL, comments TEXT DEFAULT NULL, status VARCHAR(20) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tags JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN contract_code.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contract_code.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE token (id INT NOT NULL, name VARCHAR(100) NOT NULL, code VARCHAR(5) NOT NULL, address VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, decimals INT NOT NULL, issuer VARCHAR(255) NOT NULL, locale VARCHAR(5) DEFAULT NULL, referenced_currency VARCHAR(5) DEFAULT NULL, type VARCHAR(20) DEFAULT NULL, issuer_address VARCHAR(255) DEFAULT NULL, issuer_site VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN token.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE "user" (id VARCHAR(255) NOT NULL, email VARCHAR(150) NOT NULL, name VARCHAR(150) NOT NULL, password VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE contract (id INT NOT NULL, issuer_id VARCHAR(255) NOT NULL, token_id INT NOT NULL, contract_transaction_id INT DEFAULT NULL, contract_code_id INT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, label VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, initialized_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, approved_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, rate DOUBLE PRECISION NOT NULL, initialized BOOLEAN NOT NULL, funds_reached BOOLEAN NOT NULL, status VARCHAR(100) DEFAULT NULL, claim_months INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, goal DOUBLE PRECISION NOT NULL, return_type INT DEFAULT NULL, return_months INT DEFAULT NULL, min_per_investment DOUBLE PRECISION DEFAULT NULL, short_description TEXT DEFAULT NULL, project_address VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_e98f28592f8b83df ON contract (contract_code_id)');
        $this->addSql('CREATE INDEX idx_e98f285941dee7b9 ON contract (token_id)');
        $this->addSql('CREATE INDEX idx_e98f2859bb9d6fee ON contract (issuer_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_e98f2859a749ca68 ON contract (contract_transaction_id)');
        $this->addSql('COMMENT ON COLUMN contract.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contract.initialized_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contract.approved_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE contract_withdrawal_request (id INT NOT NULL, contract_id INT NOT NULL, requested_by_id VARCHAR(255) NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, requested_amount DOUBLE PRECISION NOT NULL, status VARCHAR(25) NOT NULL, valid_until TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, uuid VARCHAR(255) NOT NULL, confirm_url TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_f0477b4a2576e0fd ON contract_withdrawal_request (contract_id)');
        $this->addSql('CREATE INDEX idx_f0477b4a4da1e751 ON contract_withdrawal_request (requested_by_id)');
        $this->addSql('COMMENT ON COLUMN contract_withdrawal_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contract_withdrawal_request.valid_until IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE contract_payment_availability (id INT NOT NULL, contract_id INT NOT NULL, contract_transaction_id INT DEFAULT NULL, required_funds DOUBLE PRECISION DEFAULT NULL, checked_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_ea1b07e2576e0fd ON contract_payment_availability (contract_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_ea1b07ea749ca68 ON contract_payment_availability (contract_transaction_id)');
        $this->addSql('COMMENT ON COLUMN contract_payment_availability.checked_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contract_payment_availability.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE system_wallet (id INT NOT NULL, blockchain_network_id INT NOT NULL, address VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, private_key JSON NOT NULL, default_wallet BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6ce627de21dd4016 ON system_wallet (blockchain_network_id)');
        $this->addSql('COMMENT ON COLUMN system_wallet.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE blockchain (id INT NOT NULL, name VARCHAR(100) NOT NULL, info_url VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN blockchain.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE contract_balance_movement (id INT NOT NULL, contract_id INT NOT NULL, contract_transaction_id INT DEFAULT NULL, requested_by_id VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, segment_from VARCHAR(20) NOT NULL, segment_to VARCHAR(20) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, moved_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_82e661382576e0fd ON contract_balance_movement (contract_id)');
        $this->addSql('CREATE INDEX idx_82e661384da1e751 ON contract_balance_movement (requested_by_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_82e66138a749ca68 ON contract_balance_movement (contract_transaction_id)');
        $this->addSql('COMMENT ON COLUMN contract_balance_movement.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contract_balance_movement.moved_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE contract_investments_pause_resume');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE contract_withdrawal_approval');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE user_contract_payment');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE contract_transaction');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE contract_reserve_fund_contribution');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE contract_balance');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE user_contract');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE user_wallet');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE blockchain_network');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE configuration');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE contract_code');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE token');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE "user"');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE contract');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE contract_withdrawal_request');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE contract_payment_availability');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE system_wallet');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE blockchain');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE contract_balance_movement');
    }
}
