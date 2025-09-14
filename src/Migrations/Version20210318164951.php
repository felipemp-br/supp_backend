<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318164951 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'migration from 1.2.1 do 1.3.0';
    }

    /**
     * @param Schema $schema
     * @throws Exception
     */
    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('CREATE SEQUENCE ad_contato_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_dado_pessoal_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_grupo_contato_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_tipo_contato_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_tipo_dado_pessoal_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE ad_contato (id NUMBER(10) NOT NULL, tipo_contato_id NUMBER(10) NOT NULL, setor_id NUMBER(10) DEFAULT NULL NULL, unidade_id NUMBER(10) DEFAULT NULL NULL, usuario_id NUMBER(10) DEFAULT NULL NULL, grupo_contato_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_ECE42C6D17F50A6 ON ad_contato (uuid)');
            $this->addSql('CREATE INDEX IDX_ECE42C623FFBC6E ON ad_contato (tipo_contato_id)');
            $this->addSql('CREATE INDEX IDX_ECE42C64D94F126 ON ad_contato (setor_id)');
            $this->addSql('CREATE INDEX IDX_ECE42C6EDF4B99B ON ad_contato (unidade_id)');
            $this->addSql('CREATE INDEX IDX_ECE42C6DB38439E ON ad_contato (usuario_id)');
            $this->addSql('CREATE INDEX IDX_ECE42C6B292F89D ON ad_contato (grupo_contato_id)');
            $this->addSql('CREATE INDEX IDX_ECE42C6F69C7D9B ON ad_contato (criado_por)');
            $this->addSql('CREATE INDEX IDX_ECE42C6AF2B1A92 ON ad_contato (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_ECE42C6A395BB94 ON ad_contato (apagado_por)');
            $this->addSql('COMMENT ON COLUMN ad_contato.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE ad_dado_pessoal (id NUMBER(10) NOT NULL, tipo_dado_pessoal_id NUMBER(10) NOT NULL, pessoa_id NUMBER(10) NOT NULL, origem_dados_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, numero_documento_principal VARCHAR2(255) NOT NULL, conteudo CLOB DEFAULT NULL NULL, numero_identificador VARCHAR2(255) DEFAULT NULL NULL, protocolo_requerimento VARCHAR2(255) DEFAULT NULL NULL, status_req_pap_get VARCHAR2(255) DEFAULT NULL NULL, versao NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_F2834A27D17F50A6 ON ad_dado_pessoal (uuid)');
            $this->addSql('CREATE INDEX IDX_F2834A27D31FD11 ON ad_dado_pessoal (tipo_dado_pessoal_id)');
            $this->addSql('CREATE INDEX IDX_F2834A27DF6FA0A5 ON ad_dado_pessoal (pessoa_id)');
            $this->addSql('CREATE INDEX IDX_F2834A27A654CBCD ON ad_dado_pessoal (origem_dados_id)');
            $this->addSql('CREATE INDEX IDX_F2834A27F69C7D9B ON ad_dado_pessoal (criado_por)');
            $this->addSql('CREATE INDEX IDX_F2834A27AF2B1A92 ON ad_dado_pessoal (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_F2834A27A395BB94 ON ad_dado_pessoal (apagado_por)');
            $this->addSql('COMMENT ON COLUMN ad_dado_pessoal.conteudo IS \'(DC2Type:json)\'');
            $this->addSql('COMMENT ON COLUMN ad_dado_pessoal.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE ad_grupo_contato (id NUMBER(10) NOT NULL, usuario_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, ativo NUMBER(1) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_51B66BCFD17F50A6 ON ad_grupo_contato (uuid)');
            $this->addSql('CREATE INDEX IDX_51B66BCFDB38439E ON ad_grupo_contato (usuario_id)');
            $this->addSql('CREATE INDEX IDX_51B66BCFF69C7D9B ON ad_grupo_contato (criado_por)');
            $this->addSql('CREATE INDEX IDX_51B66BCFAF2B1A92 ON ad_grupo_contato (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_51B66BCFA395BB94 ON ad_grupo_contato (apagado_por)');
            $this->addSql('COMMENT ON COLUMN ad_grupo_contato.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE ad_tipo_contato (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_3C353317D17F50A6 ON ad_tipo_contato (uuid)');
            $this->addSql('CREATE INDEX IDX_3C353317F69C7D9B ON ad_tipo_contato (criado_por)');
            $this->addSql('CREATE INDEX IDX_3C353317AF2B1A92 ON ad_tipo_contato (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_3C353317A395BB94 ON ad_tipo_contato (apagado_por)');
            $this->addSql('COMMENT ON COLUMN ad_tipo_contato.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE ad_tipo_dado_pessoal (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, fonte_dados VARCHAR2(255) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, sigla VARCHAR2(25) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_C1741377D17F50A6 ON ad_tipo_dado_pessoal (uuid)');
            $this->addSql('CREATE INDEX IDX_C1741377F69C7D9B ON ad_tipo_dado_pessoal (criado_por)');
            $this->addSql('CREATE INDEX IDX_C1741377AF2B1A92 ON ad_tipo_dado_pessoal (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_C1741377A395BB94 ON ad_tipo_dado_pessoal (apagado_por)');
            $this->addSql('COMMENT ON COLUMN ad_tipo_dado_pessoal.uuid IS \'(DC2Type:guid)\'');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C623FFBC6E FOREIGN KEY (tipo_contato_id) REFERENCES ad_tipo_contato (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C64D94F126 FOREIGN KEY (setor_id) REFERENCES ad_setor (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C6EDF4B99B FOREIGN KEY (unidade_id) REFERENCES ad_setor (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C6DB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C6B292F89D FOREIGN KEY (grupo_contato_id) REFERENCES ad_grupo_contato (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C6F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C6AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C6A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27D31FD11 FOREIGN KEY (tipo_dado_pessoal_id) REFERENCES ad_tipo_dado_pessoal (id)');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_grupo_contato ADD CONSTRAINT FK_51B66BCFDB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_grupo_contato ADD CONSTRAINT FK_51B66BCFF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_grupo_contato ADD CONSTRAINT FK_51B66BCFAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_grupo_contato ADD CONSTRAINT FK_51B66BCFA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_contato ADD CONSTRAINT FK_3C353317F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_contato ADD CONSTRAINT FK_3C353317AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_contato ADD CONSTRAINT FK_3C353317A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_dado_pessoal ADD CONSTRAINT FK_C1741377F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_dado_pessoal ADD CONSTRAINT FK_C1741377AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_dado_pessoal ADD CONSTRAINT FK_C1741377A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE AD_CLASSIFICACAO DROP CONSTRAINT FK_A76F45B2C580A2A2');
            $this->addSql('DROP INDEX idx_a76f45b2c580a2a2');
            $this->addSql('ALTER TABLE AD_CLASSIFICACAO ADD (visibilidade_restrita NUMBER(1) DEFAULT 0 NOT NULL)');
            $this->addSql('ALTER TABLE AD_CLASSIFICACAO DROP (TIPO_SIGILO_ID)');
            $this->addSql('ALTER TABLE AD_DOCUMENTO ADD (destinatario VARCHAR2(255) DEFAULT NULL NULL)');
            $this->addSql('DROP INDEX uniq_81f40b991a200e92');
            $this->addSql('CREATE INDEX IDX_81F40B991A200E92 ON AD_VINC_DOCUMENTO (documento_vinculado_id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_81F40B991A200E92FF6A170E ON AD_VINC_DOCUMENTO (documento_vinculado_id, apagado_em)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_8BFB16C66C3B1476FF6A170E ON AD_VINC_PROCESSO (processo_vinculado_id, apagado_em)');
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE TABLE ad_contato (id INT AUTO_INCREMENT NOT NULL, tipo_contato_id INT NOT NULL, setor_id INT DEFAULT NULL, unidade_id INT DEFAULT NULL, usuario_id INT DEFAULT NULL, grupo_contato_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_ECE42C6D17F50A6 (uuid), INDEX IDX_ECE42C623FFBC6E (tipo_contato_id), INDEX IDX_ECE42C64D94F126 (setor_id), INDEX IDX_ECE42C6EDF4B99B (unidade_id), INDEX IDX_ECE42C6DB38439E (usuario_id), INDEX IDX_ECE42C6B292F89D (grupo_contato_id), INDEX IDX_ECE42C6F69C7D9B (criado_por), INDEX IDX_ECE42C6AF2B1A92 (atualizado_por), INDEX IDX_ECE42C6A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_dado_pessoal (id INT AUTO_INCREMENT NOT NULL, tipo_dado_pessoal_id INT NOT NULL, pessoa_id INT NOT NULL, origem_dados_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, numero_documento_principal VARCHAR(255) NOT NULL, conteudo JSON DEFAULT NULL, numero_identificador VARCHAR(255) DEFAULT NULL, protocolo_requerimento VARCHAR(255) DEFAULT NULL, status_req_pap_get VARCHAR(255) DEFAULT NULL, versao INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_F2834A27D17F50A6 (uuid), INDEX IDX_F2834A27D31FD11 (tipo_dado_pessoal_id), INDEX IDX_F2834A27DF6FA0A5 (pessoa_id), INDEX IDX_F2834A27A654CBCD (origem_dados_id), INDEX IDX_F2834A27F69C7D9B (criado_por), INDEX IDX_F2834A27AF2B1A92 (atualizado_por), INDEX IDX_F2834A27A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_grupo_contato (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, ativo TINYINT(1) NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_51B66BCFD17F50A6 (uuid), INDEX IDX_51B66BCFDB38439E (usuario_id), INDEX IDX_51B66BCFF69C7D9B (criado_por), INDEX IDX_51B66BCFAF2B1A92 (atualizado_por), INDEX IDX_51B66BCFA395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_tipo_contato (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_3C353317D17F50A6 (uuid), INDEX IDX_3C353317F69C7D9B (criado_por), INDEX IDX_3C353317AF2B1A92 (atualizado_por), INDEX IDX_3C353317A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_tipo_dado_pessoal (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, fonte_dados VARCHAR(255) DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, sigla VARCHAR(25) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C1741377D17F50A6 (uuid), INDEX IDX_C1741377F69C7D9B (criado_por), INDEX IDX_C1741377AF2B1A92 (atualizado_por), INDEX IDX_C1741377A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C623FFBC6E FOREIGN KEY (tipo_contato_id) REFERENCES ad_tipo_contato (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C64D94F126 FOREIGN KEY (setor_id) REFERENCES ad_setor (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C6EDF4B99B FOREIGN KEY (unidade_id) REFERENCES ad_setor (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C6DB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C6B292F89D FOREIGN KEY (grupo_contato_id) REFERENCES ad_grupo_contato (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C6F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C6AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_contato ADD CONSTRAINT FK_ECE42C6A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27D31FD11 FOREIGN KEY (tipo_dado_pessoal_id) REFERENCES ad_tipo_dado_pessoal (id)');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_grupo_contato ADD CONSTRAINT FK_51B66BCFDB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_grupo_contato ADD CONSTRAINT FK_51B66BCFF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_grupo_contato ADD CONSTRAINT FK_51B66BCFAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_grupo_contato ADD CONSTRAINT FK_51B66BCFA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_contato ADD CONSTRAINT FK_3C353317F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_contato ADD CONSTRAINT FK_3C353317AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_contato ADD CONSTRAINT FK_3C353317A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_dado_pessoal ADD CONSTRAINT FK_C1741377F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_dado_pessoal ADD CONSTRAINT FK_C1741377AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_dado_pessoal ADD CONSTRAINT FK_C1741377A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_classificacao DROP FOREIGN KEY FK_A76F45B2C580A2A2');
            $this->addSql('DROP INDEX IDX_A76F45B2C580A2A2 ON ad_classificacao');
            $this->addSql('ALTER TABLE ad_classificacao ADD visibilidade_restrita TINYINT(1) NOT NULL, DROP tipo_sigilo_id');
            $this->addSql('ALTER TABLE ad_documento ADD destinatario VARCHAR(255) DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_vinc_documento DROP INDEX UNIQ_81F40B991A200E92, ADD INDEX IDX_81F40B991A200E92 (documento_vinculado_id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_81F40B991A200E92FF6A170E ON ad_vinc_documento (documento_vinculado_id, apagado_em)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_8BFB16C66C3B1476FF6A170E ON ad_vinc_processo (processo_vinculado_id, apagado_em)');
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
            $this->addSql('ALTER TABLE ad_contato DROP CONSTRAINT FK_ECE42C6B292F89D');
            $this->addSql('ALTER TABLE ad_contato DROP CONSTRAINT FK_ECE42C623FFBC6E');
            $this->addSql('ALTER TABLE ad_dado_pessoal DROP CONSTRAINT FK_F2834A27D31FD11');
            $this->addSql('DROP SEQUENCE ad_contato_id_seq');
            $this->addSql('DROP SEQUENCE ad_dado_pessoal_id_seq');
            $this->addSql('DROP SEQUENCE ad_grupo_contato_id_seq');
            $this->addSql('DROP SEQUENCE ad_tipo_contato_id_seq');
            $this->addSql('DROP SEQUENCE ad_tipo_dado_pessoal_id_seq');
            $this->addSql('DROP TABLE ad_contato');
            $this->addSql('DROP TABLE ad_dado_pessoal');
            $this->addSql('DROP TABLE ad_grupo_contato');
            $this->addSql('DROP TABLE ad_tipo_contato');
            $this->addSql('DROP TABLE ad_tipo_dado_pessoal');
            $this->addSql('ALTER TABLE ad_classificacao ADD (TIPO_SIGILO_ID NUMBER(10) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE ad_classificacao DROP (visibilidade_restrita)');
            $this->addSql('ALTER TABLE ad_classificacao ADD CONSTRAINT FK_A76F45B2C580A2A2 FOREIGN KEY (TIPO_SIGILO_ID) REFERENCES AD_TIPO_SIGILO (ID)');
            $this->addSql('CREATE INDEX idx_a76f45b2c580a2a2 ON ad_classificacao (TIPO_SIGILO_ID)');
            $this->addSql('ALTER TABLE ad_documento DROP (destinatario)');
            $this->addSql('DROP INDEX IDX_81F40B991A200E92');
            $this->addSql('DROP INDEX UNIQ_81F40B991A200E92FF6A170E');
            $this->addSql('CREATE UNIQUE INDEX uniq_81f40b991a200e92 ON ad_vinc_documento (DOCUMENTO_VINCULADO_ID)');
            $this->addSql('DROP INDEX UNIQ_8BFB16C66C3B1476FF6A170E');
        } else {
            $this->addSql('ALTER TABLE ad_contato DROP FOREIGN KEY FK_ECE42C6B292F89D');
            $this->addSql('ALTER TABLE ad_contato DROP FOREIGN KEY FK_ECE42C623FFBC6E');
            $this->addSql('ALTER TABLE ad_dado_pessoal DROP FOREIGN KEY FK_F2834A27D31FD11');
            $this->addSql('DROP TABLE ad_contato');
            $this->addSql('DROP TABLE ad_dado_pessoal');
            $this->addSql('DROP TABLE ad_grupo_contato');
            $this->addSql('DROP TABLE ad_tipo_contato');
            $this->addSql('DROP TABLE ad_tipo_dado_pessoal');
            $this->addSql('ALTER TABLE ad_classificacao ADD tipo_sigilo_id INT DEFAULT NULL, DROP visibilidade_restrita');
            $this->addSql('ALTER TABLE ad_classificacao ADD CONSTRAINT FK_A76F45B2C580A2A2 FOREIGN KEY (tipo_sigilo_id) REFERENCES ad_tipo_sigilo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('CREATE INDEX IDX_A76F45B2C580A2A2 ON ad_classificacao (tipo_sigilo_id)');
            $this->addSql('ALTER TABLE ad_documento DROP destinatario');
            $this->addSql('ALTER TABLE ad_vinc_documento DROP INDEX IDX_81F40B991A200E92, ADD UNIQUE INDEX UNIQ_81F40B991A200E92 (documento_vinculado_id)');
            $this->addSql('DROP INDEX UNIQ_81F40B991A200E92FF6A170E ON ad_vinc_documento');
            $this->addSql('DROP INDEX UNIQ_8BFB16C66C3B1476FF6A170E ON ad_vinc_processo');
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
