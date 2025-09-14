<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223223521Consultivo extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.14.0 to 1.15.0';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('CREATE SEQUENCE cs_atividade_consultiva_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE cs_complemento_consultivo_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');

            $this->addSql('CREATE TABLE cs_atividade_consultiva (id NUMBER(10) NOT NULL, atividade_id NUMBER(10) NOT NULL, complemento_consultivo_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE INDEX IDX_CBB3C8C7A22D979B ON cs_atividade_consultiva (atividade_id)');
            $this->addSql('CREATE INDEX IDX_CBB3C8C7BA7C7AA2 ON cs_atividade_consultiva (complemento_consultivo_id)');
            $this->addSql('CREATE INDEX IDX_CBB3C8C7F69C7D9B ON cs_atividade_consultiva (criado_por)');
            $this->addSql('CREATE INDEX IDX_CBB3C8C7AF2B1A92 ON cs_atividade_consultiva (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_CBB3C8C7A395BB94 ON cs_atividade_consultiva (apagado_por)');
            $this->addSql('COMMENT ON COLUMN cs_atividade_consultiva.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE cs_complemento_consultivo (id NUMBER(10) NOT NULL, unidade_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) DEFAULT NULL NULL, descricao VARCHAR2(255) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_FF5D958654BD530C ON cs_complemento_consultivo (nome)');
            $this->addSql('CREATE INDEX IDX_FF5D9586EDF4B99B ON cs_complemento_consultivo (unidade_id)');
            $this->addSql('CREATE INDEX IDX_FF5D9586F69C7D9B ON cs_complemento_consultivo (criado_por)');
            $this->addSql('CREATE INDEX IDX_FF5D9586AF2B1A92 ON cs_complemento_consultivo (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_FF5D9586A395BB94 ON cs_complemento_consultivo (apagado_por)');
            $this->addSql('COMMENT ON COLUMN cs_complemento_consultivo.uuid IS \'(DC2Type:guid)\'');

            $this->addSql('ALTER TABLE cs_atividade_consultiva ADD CONSTRAINT FK_CBB3C8C7A22D979B FOREIGN KEY (atividade_id) REFERENCES ad_atividade (id)');
            $this->addSql('ALTER TABLE cs_atividade_consultiva ADD CONSTRAINT FK_CBB3C8C7BA7C7AA2 FOREIGN KEY (complemento_consultivo_id) REFERENCES cs_complemento_consultivo (id)');
            $this->addSql('ALTER TABLE cs_atividade_consultiva ADD CONSTRAINT FK_CBB3C8C7F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE cs_atividade_consultiva ADD CONSTRAINT FK_CBB3C8C7AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE cs_atividade_consultiva ADD CONSTRAINT FK_CBB3C8C7A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE cs_complemento_consultivo ADD CONSTRAINT FK_FF5D9586EDF4B99B FOREIGN KEY (unidade_id) REFERENCES ad_setor (id)');
            $this->addSql('ALTER TABLE cs_complemento_consultivo ADD CONSTRAINT FK_FF5D9586F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE cs_complemento_consultivo ADD CONSTRAINT FK_FF5D9586AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE cs_complemento_consultivo ADD CONSTRAINT FK_FF5D9586A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

        } else {
            $this->addSql('CREATE TABLE cs_atividade_consultiva (id INT AUTO_INCREMENT NOT NULL, atividade_id INT NOT NULL, complemento_consultivo_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, INDEX IDX_CBB3C8C7A22D979B (atividade_id), INDEX IDX_CBB3C8C7BA7C7AA2 (complemento_consultivo_id), INDEX IDX_CBB3C8C7F69C7D9B (criado_por), INDEX IDX_CBB3C8C7AF2B1A92 (atualizado_por), INDEX IDX_CBB3C8C7A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE cs_complemento_consultivo (id INT AUTO_INCREMENT NOT NULL, unidade_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) DEFAULT NULL, descricao VARCHAR(255) DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_FF5D958654BD530C (nome), INDEX IDX_FF5D9586EDF4B99B (unidade_id), INDEX IDX_FF5D9586F69C7D9B (criado_por), INDEX IDX_FF5D9586AF2B1A92 (atualizado_por), INDEX IDX_FF5D9586A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

            $this->addSql('ALTER TABLE cs_atividade_consultiva ADD CONSTRAINT FK_CBB3C8C7A22D979B FOREIGN KEY (atividade_id) REFERENCES ad_atividade (id)');
            $this->addSql('ALTER TABLE cs_atividade_consultiva ADD CONSTRAINT FK_CBB3C8C7BA7C7AA2 FOREIGN KEY (complemento_consultivo_id) REFERENCES cs_complemento_consultivo (id)');
            $this->addSql('ALTER TABLE cs_atividade_consultiva ADD CONSTRAINT FK_CBB3C8C7F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE cs_atividade_consultiva ADD CONSTRAINT FK_CBB3C8C7AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE cs_atividade_consultiva ADD CONSTRAINT FK_CBB3C8C7A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE cs_complemento_consultivo ADD CONSTRAINT FK_FF5D9586EDF4B99B FOREIGN KEY (unidade_id) REFERENCES ad_setor (id)');
            $this->addSql('ALTER TABLE cs_complemento_consultivo ADD CONSTRAINT FK_FF5D9586F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE cs_complemento_consultivo ADD CONSTRAINT FK_FF5D9586AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE cs_complemento_consultivo ADD CONSTRAINT FK_FF5D9586A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('DROP SEQUENCE cs_atividade_consultiva_id_seq');
            $this->addSql('DROP SEQUENCE cs_complemento_consultivo_id_seq');

            $this->addSql('ALTER TABLE cs_atividade_consultiva DROP CONSTRAINT FK_CBB3C8C7A22D979B');
            $this->addSql('ALTER TABLE cs_atividade_consultiva DROP CONSTRAINT FK_CBB3C8C7BA7C7AA2');
            $this->addSql('ALTER TABLE cs_atividade_consultiva DROP CONSTRAINT FK_CBB3C8C7F69C7D9B');
            $this->addSql('ALTER TABLE cs_atividade_consultiva DROP CONSTRAINT FK_CBB3C8C7AF2B1A92');
            $this->addSql('ALTER TABLE cs_atividade_consultiva DROP CONSTRAINT FK_CBB3C8C7A395BB94');
            $this->addSql('ALTER TABLE cs_complemento_consultivo DROP CONSTRAINT FK_FF5D9586EDF4B99B');
            $this->addSql('ALTER TABLE cs_complemento_consultivo DROP CONSTRAINT FK_FF5D9586F69C7D9B');
            $this->addSql('ALTER TABLE cs_complemento_consultivo DROP CONSTRAINT FK_FF5D9586AF2B1A92');
            $this->addSql('ALTER TABLE cs_complemento_consultivo DROP CONSTRAINT FK_FF5D9586A395BB94');

            $this->addSql('DROP TABLE cs_atividade_consultiva');
            $this->addSql('DROP TABLE cs_complemento_consultivo');

        } else {

            $this->addSql('ALTER TABLE cs_atividade_consultiva DROP FOREIGN KEY FK_CBB3C8C7A22D979B');
            $this->addSql('ALTER TABLE cs_atividade_consultiva DROP FOREIGN KEY FK_CBB3C8C7BA7C7AA2');
            $this->addSql('ALTER TABLE cs_atividade_consultiva DROP FOREIGN KEY FK_CBB3C8C7F69C7D9B');
            $this->addSql('ALTER TABLE cs_atividade_consultiva DROP FOREIGN KEY FK_CBB3C8C7AF2B1A92');
            $this->addSql('ALTER TABLE cs_atividade_consultiva DROP FOREIGN KEY FK_CBB3C8C7A395BB94');
            $this->addSql('ALTER TABLE cs_complemento_consultivo DROP FOREIGN KEY FK_FF5D9586EDF4B99B');
            $this->addSql('ALTER TABLE cs_complemento_consultivo DROP FOREIGN KEY FK_FF5D9586F69C7D9B');
            $this->addSql('ALTER TABLE cs_complemento_consultivo DROP FOREIGN KEY FK_FF5D9586AF2B1A92');
            $this->addSql('ALTER TABLE cs_complemento_consultivo DROP FOREIGN KEY FK_FF5D9586A395BB94');
            $this->addSql('DROP TABLE cs_atividade_consultiva');
            $this->addSql('DROP TABLE cs_complemento_consultivo');
        }
    }
}
