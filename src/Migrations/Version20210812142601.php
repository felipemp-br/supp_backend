<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210812142601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.6.4 do 1.6.5';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_relatorio ADD (status NUMBER(1) DEFAULT NULL NULL)');
        } else {
            $this->addSql('ALTER TABLE ad_relatorio ADD status INT DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE ad_relatorio DROP (status)');
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_relatorio DROP status');
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
