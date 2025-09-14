<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223223521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.14.0 to 1.15.0';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE AD_CRONJOB ADD (timeout NUMBER(10) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_DOSSIE ADD (visibilidade NUMBER(10) DEFAULT 0 NULL)');
            $this->addSql('ALTER TABLE AD_MUNICIPIO ADD (codigo_siafi VARCHAR2(255) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_PAIS ADD (codigo_receita_federal VARCHAR2(255) DEFAULT NULL NULL, nome_receita_federal VARCHAR2(255) DEFAULT NULL NULL)');

        } else {
            $this->addSql('ALTER TABLE ad_cronjob ADD timeout INT DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_dossie ADD visibilidade INT UNSIGNED DEFAULT 0');
            $this->addSql('ALTER TABLE ad_municipio ADD codigo_siafi VARCHAR(255) DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_pais ADD codigo_receita_federal VARCHAR(255) DEFAULT NULL, ADD nome_receita_federal VARCHAR(255) DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE ad_municipio DROP (codigo_siafi)');
            $this->addSql('ALTER TABLE ad_dossie DROP (visibilidade)');
            $this->addSql('ALTER TABLE ad_cronjob DROP (timeout)');
            $this->addSql('ALTER TABLE ad_pais DROP (codigo_receita_federal, nome_receita_federal)');
        } else {
            $this->addSql('ALTER TABLE ad_cronjob DROP timeout');
            $this->addSql('ALTER TABLE ad_dossie DROP visibilidade');
            $this->addSql('ALTER TABLE ad_municipio DROP codigo_siafi');
            $this->addSql('ALTER TABLE ad_pais DROP codigo_receita_federal, DROP nome_receita_federal');

        }
    }
}
