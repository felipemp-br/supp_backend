<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513191746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.8.6 to 1.8.7';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
                $this->addSql('ALTER TABLE ad_classificacao MODIFY (OBSERVACAO VARCHAR2(500) DEFAULT NULL)');
        } else {
            $this->addSql('ALTER TABLE ad_classificacao CHANGE COLUMN observacao observacao VARCHAR(500) DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE ad_classificacao MODIFY (OBSERVACAO VARCHAR2(255) DEFAULT NULL)');
        } else {
            $this->addSql('ALTER TABLE ad_classificacao CHANGE COLUMN observacao observacao VARCHAR(255) DEFAULT NULL');
        }
    }
}
