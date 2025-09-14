<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220916132737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.9.2 to 1.9.3';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE AD_CONFIG_MODULO ADD (SIGLA VARCHAR(255) DEFAULT NULL NULL)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_57ABEDDE801B7D4BFF6A170E ON AD_CONFIG_MODULO (sigla, apagado_em)');
            $this->addSql('ALTER TABLE AD_CRONJOB ADD (sincrono NUMBER(1) DEFAULT NULL NULL)');
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_config_modulo ADD sigla VARCHAR(255) DEFAULT NULL');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_57ABEDDE801B7D4BFF6A170E ON ad_config_modulo (sigla, apagado_em)');
            $this->addSql('ALTER TABLE ad_cronjob ADD sincrono TINYINT(1) NOT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('DROP INDEX UNIQ_57ABEDDE801B7D4BFF6A170E');
            $this->addSql('ALTER TABLE AD_CONFIG_MODULO DROP (sigla)');
            $this->addSql('ALTER TABLE AD_CRONJOB DROP (sincrono)');
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('DROP INDEX UNIQ_57ABEDDE801B7D4BFF6A170E ON ad_config_modulo');
            $this->addSql('ALTER TABLE ad_config_modulo DROP sigla');
            $this->addSql('ALTER TABLE ad_cronjob DROP sincrono');
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
