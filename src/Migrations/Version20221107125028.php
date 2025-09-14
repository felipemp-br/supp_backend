<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221107125028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.9.3 to 1.10.0';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE AD_COMPONENTE_DIGITAL ADD (interacoes NUMBER(10) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_ORIGEM_DADOS ADD (msg_ultima_consulta VARCHAR2(255) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_TIPO_DOSSIE ADD (datalake NUMBER(1) DEFAULT NULL NULL)');
        } else {
            $this->addSql('ALTER TABLE ad_componente_digital ADD interacoes INT DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_origem_dados ADD msg_ultima_consulta VARCHAR(255) DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_tipo_dossie ADD datalake TINYINT(1) DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE ad_componente_digital DROP (interacoes)');
            $this->addSql('ALTER TABLE ad_origem_dados DROP (msg_ultima_consulta)');
            $this->addSql('ALTER TABLE ad_tipo_dossie DROP (datalake)');
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_componente_digital DROP interacoes');
            $this->addSql('ALTER TABLE ad_origem_dados DROP msg_ultima_consulta');
            $this->addSql('ALTER TABLE ad_tipo_dossie DROP datalake');
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
