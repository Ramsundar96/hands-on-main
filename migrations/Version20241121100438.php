<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121100438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Bank account event store';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            sql: <<<'EOD'
                CREATE TABLE bank_account_event_store (
                    event_id UUID PRIMARY KEY,
                    aggregate_root_id STRING(253) NOT NULL,
                    version INT NOT NULL,
                    payload JSONB NOT NULL,
                    UNIQUE (aggregate_root_id, version)
                );
                CREATE INDEX idx_aggregate_root_id ON bank_account_event_store (aggregate_root_id);

                
EOD
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            sql: 'DROP TABLE bank_account_event_store'
        );
    }
}
