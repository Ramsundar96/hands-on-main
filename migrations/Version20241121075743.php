<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121075743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Bank account projection';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            sql: <<<'EOD'
                CREATE TABLE bank_account_projection (
                    bank_account_id UUID PRIMARY KEY,
                    account_holder_name VARCHAR(255) NOT NULL,
                    balance DOUBLE PRECISION NOT NULL,
                    account_type VARCHAR(16) NOT NULL,
                    currency_code VARCHAR(3) NOT NULL,
                    overdraft_limit DOUBLE PRECISION NOT NULL
                )
EOD);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            sql: 'DROP TABLE bank_account_projection'
        );
    }
}
