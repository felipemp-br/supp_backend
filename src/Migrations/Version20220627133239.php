<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627133239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.8.10 to 1.8.11';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE ad_tipo_relatorio MODIFY (parametros DEFAULT NULL NULL)');
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_tipo_relatorio CHANGE parametros parametros LONGTEXT DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE ad_tipo_relatorio MODIFY (parametros NOT NULL)');
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_tipo_relatorio CHANGE parametros parametros LONGTEXT NOT NULL');
        }
    }
}
