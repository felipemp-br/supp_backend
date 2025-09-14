<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210504175429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'migration from 1.3.1 do 1.3.2';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE ad_processo ADD (lembrete_arquivista VARCHAR(255) DEFAULT NULL)');
        } else {
            $this->addSql('ALTER TABLE ad_processo ADD lembrete_arquivista VARCHAR(255) DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE ad_processo DROP lembrete_arquivista');
        } else {
            $this->addSql('ALTER TABLE ad_processo DROP lembrete_arquivista');
        }
    }

    /**
     * @return bool
     */
    public function isTransactional(): bool
    {
        return false;
    }
}
