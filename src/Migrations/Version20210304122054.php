<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210304122054 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'migration from 1.0.2 do 1.1.0';
    }

    /**
     * @param Schema $schema
     * @throws Exception
     */
    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('CREATE SEQUENCE ad_configuracao_nup_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE ad_configuracao_nup (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, data_hora_inicio_vigencia TIMESTAMP(0) NOT NULL, data_hora_fim_vigencia TIMESTAMP(0) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, sigla VARCHAR2(25) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_4661DCE2D17F50A6 ON ad_configuracao_nup (uuid)');
            $this->addSql('CREATE INDEX IDX_4661DCE2F69C7D9B ON ad_configuracao_nup (criado_por)');
            $this->addSql('CREATE INDEX IDX_4661DCE2AF2B1A92 ON ad_configuracao_nup (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_4661DCE2A395BB94 ON ad_configuracao_nup (apagado_por)');
            $this->addSql('ALTER TABLE ad_processo ADD (configuracao_nup_id NUMBER(10) DEFAULT NULL NULL, nup_invalido NUMBER(1) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE ad_processo ADD CONSTRAINT FK_FCFDB5FD24B6A0AE FOREIGN KEY (configuracao_nup_id) REFERENCES ad_configuracao_nup (id)');
            $this->addSql('CREATE INDEX IDX_FCFDB5FD24B6A0AE ON ad_processo (configuracao_nup_id)');
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE TABLE ad_configuracao_nup (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, data_hora_inicio_vigencia DATETIME NOT NULL, data_hora_fim_vigencia DATETIME DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL, nome VARCHAR(255) NOT NULL, sigla VARCHAR(25) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_4661DCE2D17F50A6 (uuid), INDEX IDX_4661DCE2F69C7D9B (criado_por), INDEX IDX_4661DCE2AF2B1A92 (atualizado_por), INDEX IDX_4661DCE2A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE ad_configuracao_nup ADD CONSTRAINT FK_4661DCE2F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_configuracao_nup ADD CONSTRAINT FK_4661DCE2AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_configuracao_nup ADD CONSTRAINT FK_4661DCE2A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_processo ADD configuracao_nup_id INT DEFAULT NULL, ADD nup_invalido TINYINT(1) DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_processo ADD CONSTRAINT FK_FCFDB5FD24B6A0AE FOREIGN KEY (configuracao_nup_id) REFERENCES ad_configuracao_nup (id)');
            $this->addSql('CREATE INDEX IDX_FCFDB5FD24B6A0AE ON ad_processo (configuracao_nup_id)');
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE pastas DROP CONSTRAINT FK_FCFDB5FD24B6A0AE');
            $this->addSql('DROP TABLE ad_configuracao_nup');
            $this->addSql('DROP INDEX IDX_FCFDB5FD24B6A0AE');
            $this->addSql('ALTER TABLE pastas DROP (configuracao_nup_id, nup_invalido)');
            $this->addSql('DROP SEQUENCE ad_configuracao_nup_id_seq');
        } else {
            $this->addSql('ALTER TABLE ad_processo DROP FOREIGN KEY FK_FCFDB5FD24B6A0AE');
            $this->addSql('DROP TABLE ad_configuracao_nup');
            $this->addSql('DROP INDEX IDX_FCFDB5FD24B6A0AE ON ad_processo');
            $this->addSql('ALTER TABLE ad_processo DROP configuracao_nup_id, DROP nup_invalido');
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
