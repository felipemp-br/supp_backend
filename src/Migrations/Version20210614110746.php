<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210614110746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.4.1 do 1.4.2';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE AD_DADO_PESSOAL DROP CONSTRAINT FK_F2834A27D31FD11');
            $this->addSql('DROP SEQUENCE AD_DADO_PESSOAL_ID_SEQ');
            $this->addSql('DROP SEQUENCE AD_TIPO_DADO_PESSOAL_ID_SEQ');
            $this->addSql('CREATE SEQUENCE ad_dossie_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_tipo_dossie_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE ad_dossie (id NUMBER(10) NOT NULL, tipo_dossie_id NUMBER(10) DEFAULT NULL NULL, pessoa_id NUMBER(10) NOT NULL, origem_dados_id NUMBER(10) NOT NULL, documento_id NUMBER(10) DEFAULT NULL NULL, processo_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, numero_documento_principal VARCHAR2(255) NOT NULL, conteudo CLOB DEFAULT NULL NULL, data_consulta TIMESTAMP(0) DEFAULT NULL NULL, protocolo_requerimento VARCHAR2(255) DEFAULT NULL NULL, status_requerimento VARCHAR2(255) DEFAULT NULL NULL, fonte_dados VARCHAR2(255) DEFAULT NULL NULL, versao NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_2F087C4D17F50A6 ON ad_dossie (uuid)');
            $this->addSql('CREATE INDEX IDX_2F087C45DCF72A9 ON ad_dossie (tipo_dossie_id)');
            $this->addSql('CREATE INDEX IDX_2F087C4DF6FA0A5 ON ad_dossie (pessoa_id)');
            $this->addSql('CREATE INDEX IDX_2F087C4A654CBCD ON ad_dossie (origem_dados_id)');
            $this->addSql('CREATE INDEX IDX_2F087C445C0CF75 ON ad_dossie (documento_id)');
            $this->addSql('CREATE INDEX IDX_2F087C4AAA822D2 ON ad_dossie (processo_id)');
            $this->addSql('CREATE INDEX IDX_2F087C4F69C7D9B ON ad_dossie (criado_por)');
            $this->addSql('CREATE INDEX IDX_2F087C4AF2B1A92 ON ad_dossie (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_2F087C4A395BB94 ON ad_dossie (apagado_por)');
            $this->addSql('CREATE TABLE ad_tipo_dossie (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, periodo_guarda NUMBER(10) NOT NULL, fonte_dados VARCHAR2(255) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, sigla VARCHAR2(25) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_21EDB5E8D17F50A6 ON ad_tipo_dossie (uuid)');
            $this->addSql('CREATE INDEX IDX_21EDB5E8F69C7D9B ON ad_tipo_dossie (criado_por)');
            $this->addSql('CREATE INDEX IDX_21EDB5E8AF2B1A92 ON ad_tipo_dossie (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_21EDB5E8A395BB94 ON ad_tipo_dossie (apagado_por)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C45DCF72A9 FOREIGN KEY (tipo_dossie_id) REFERENCES ad_tipo_dossie (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C4DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C4A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C445C0CF75 FOREIGN KEY (documento_id) REFERENCES ad_documento (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C4AAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C4F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C4AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C4A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_dossie ADD CONSTRAINT FK_21EDB5E8F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_dossie ADD CONSTRAINT FK_21EDB5E8AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_dossie ADD CONSTRAINT FK_21EDB5E8A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('DROP TABLE AD_DADO_PESSOAL');
            $this->addSql('DROP TABLE AD_TIPO_DADO_PESSOAL');
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_dado_pessoal DROP FOREIGN KEY FK_F2834A27D31FD11');
            $this->addSql('CREATE TABLE ad_dossie (id INT AUTO_INCREMENT NOT NULL, tipo_dossie_id INT DEFAULT NULL, pessoa_id INT NOT NULL, origem_dados_id INT NOT NULL, documento_id INT DEFAULT NULL, processo_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, numero_documento_principal VARCHAR(255) NOT NULL, conteudo JSON DEFAULT NULL, data_consulta DATETIME DEFAULT NULL, protocolo_requerimento VARCHAR(255) DEFAULT NULL, status_requerimento VARCHAR(255) DEFAULT NULL, fonte_dados VARCHAR(255) DEFAULT NULL, versao INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_2F087C4D17F50A6 (uuid), INDEX IDX_2F087C45DCF72A9 (tipo_dossie_id), INDEX IDX_2F087C4DF6FA0A5 (pessoa_id), INDEX IDX_2F087C4A654CBCD (origem_dados_id), INDEX IDX_2F087C445C0CF75 (documento_id), INDEX IDX_2F087C4AAA822D2 (processo_id), INDEX IDX_2F087C4F69C7D9B (criado_por), INDEX IDX_2F087C4AF2B1A92 (atualizado_por), INDEX IDX_2F087C4A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_tipo_dossie (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, periodo_guarda INT NOT NULL, fonte_dados VARCHAR(255) DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, sigla VARCHAR(25) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_21EDB5E8D17F50A6 (uuid), INDEX IDX_21EDB5E8F69C7D9B (criado_por), INDEX IDX_21EDB5E8AF2B1A92 (atualizado_por), INDEX IDX_21EDB5E8A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C45DCF72A9 FOREIGN KEY (tipo_dossie_id) REFERENCES ad_tipo_dossie (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C4DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C4A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C445C0CF75 FOREIGN KEY (documento_id) REFERENCES ad_documento (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C4AAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C4F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C4AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dossie ADD CONSTRAINT FK_2F087C4A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_dossie ADD CONSTRAINT FK_21EDB5E8F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_dossie ADD CONSTRAINT FK_21EDB5E8AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tipo_dossie ADD CONSTRAINT FK_21EDB5E8A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('DROP TABLE ad_dado_pessoal');
            $this->addSql('DROP TABLE ad_tipo_dado_pessoal');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_dossie DROP CONSTRAINT FK_2F087C45DCF72A9');
            $this->addSql('DROP SEQUENCE ad_dossie_id_seq');
            $this->addSql('DROP SEQUENCE ad_tipo_dossie_id_seq');
            $this->addSql('CREATE SEQUENCE AD_DADO_PESSOAL_ID_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE AD_TIPO_DADO_PESSOAL_ID_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE AD_DADO_PESSOAL (ID NUMBER(10) NOT NULL, TIPO_DADO_PESSOAL_ID NUMBER(10) NOT NULL, PESSOA_ID NUMBER(10) NOT NULL, ORIGEM_DADOS_ID NUMBER(10) NOT NULL, CRIADO_POR NUMBER(10) DEFAULT NULL NULL, ATUALIZADO_POR NUMBER(10) DEFAULT NULL NULL, APAGADO_POR NUMBER(10) DEFAULT NULL NULL, NUMERO_DOCUMENTO_PRINCIPAL VARCHAR2(255) NOT NULL, CONTEUDO CLOB DEFAULT NULL NULL, NUMERO_IDENTIFICADOR VARCHAR2(255) DEFAULT NULL NULL, PROTOCOLO_REQUERIMENTO VARCHAR2(255) DEFAULT NULL NULL, STATUS_REQ_PAP_GET VARCHAR2(255) DEFAULT NULL NULL, VERSAO NUMBER(10) DEFAULT NULL NULL, CRIADO_EM TIMESTAMP(0) DEFAULT NULL NULL, ATUALIZADO_EM TIMESTAMP(0) DEFAULT NULL NULL, APAGADO_EM TIMESTAMP(0) DEFAULT NULL NULL, UUID CHAR(36) NOT NULL, PRIMARY KEY(ID))');
            $this->addSql('CREATE UNIQUE INDEX uniq_f2834a27d17f50a6 ON AD_DADO_PESSOAL (UUID)');
            $this->addSql('CREATE INDEX idx_f2834a27d31fd11 ON AD_DADO_PESSOAL (TIPO_DADO_PESSOAL_ID)');
            $this->addSql('CREATE INDEX idx_f2834a27a395bb94 ON AD_DADO_PESSOAL (APAGADO_POR)');
            $this->addSql('CREATE INDEX idx_f2834a27a654cbcd ON AD_DADO_PESSOAL (ORIGEM_DADOS_ID)');
            $this->addSql('CREATE INDEX idx_f2834a27f69c7d9b ON AD_DADO_PESSOAL (CRIADO_POR)');
            $this->addSql('CREATE INDEX idx_f2834a27af2b1a92 ON AD_DADO_PESSOAL (ATUALIZADO_POR)');
            $this->addSql('CREATE INDEX idx_f2834a27df6fa0a5 ON AD_DADO_PESSOAL (PESSOA_ID)');
            $this->addSql('CREATE TABLE AD_TIPO_DADO_PESSOAL (ID NUMBER(10) NOT NULL, CRIADO_POR NUMBER(10) DEFAULT NULL NULL, ATUALIZADO_POR NUMBER(10) DEFAULT NULL NULL, APAGADO_POR NUMBER(10) DEFAULT NULL NULL, FONTE_DADOS VARCHAR2(255) DEFAULT NULL NULL, CRIADO_EM TIMESTAMP(0) DEFAULT NULL NULL, ATUALIZADO_EM TIMESTAMP(0) DEFAULT NULL NULL, APAGADO_EM TIMESTAMP(0) DEFAULT NULL NULL, UUID CHAR(36) NOT NULL, NOME VARCHAR2(255) NOT NULL, DESCRICAO VARCHAR2(255) NOT NULL, SIGLA VARCHAR2(25) NOT NULL, ATIVO NUMBER(1) NOT NULL, PRIMARY KEY(ID))');
            $this->addSql('CREATE UNIQUE INDEX uniq_c1741377d17f50a6 ON AD_TIPO_DADO_PESSOAL (UUID)');
            $this->addSql('CREATE INDEX idx_c1741377a395bb94 ON AD_TIPO_DADO_PESSOAL (APAGADO_POR)');
            $this->addSql('CREATE INDEX idx_c1741377af2b1a92 ON AD_TIPO_DADO_PESSOAL (ATUALIZADO_POR)');
            $this->addSql('CREATE INDEX idx_c1741377f69c7d9b ON AD_TIPO_DADO_PESSOAL (CRIADO_POR)');
            $this->addSql('ALTER TABLE AD_DADO_PESSOAL ADD CONSTRAINT FK_F2834A27A395BB94 FOREIGN KEY (APAGADO_POR) REFERENCES AD_USUARIO (ID)');
            $this->addSql('ALTER TABLE AD_DADO_PESSOAL ADD CONSTRAINT FK_F2834A27A654CBCD FOREIGN KEY (ORIGEM_DADOS_ID) REFERENCES AD_ORIGEM_DADOS (ID)');
            $this->addSql('ALTER TABLE AD_DADO_PESSOAL ADD CONSTRAINT FK_F2834A27AF2B1A92 FOREIGN KEY (ATUALIZADO_POR) REFERENCES AD_USUARIO (ID)');
            $this->addSql('ALTER TABLE AD_DADO_PESSOAL ADD CONSTRAINT FK_F2834A27D31FD11 FOREIGN KEY (TIPO_DADO_PESSOAL_ID) REFERENCES AD_TIPO_DADO_PESSOAL (ID)');
            $this->addSql('ALTER TABLE AD_DADO_PESSOAL ADD CONSTRAINT FK_F2834A27DF6FA0A5 FOREIGN KEY (PESSOA_ID) REFERENCES AD_PESSOA (ID)');
            $this->addSql('ALTER TABLE AD_DADO_PESSOAL ADD CONSTRAINT FK_F2834A27F69C7D9B FOREIGN KEY (CRIADO_POR) REFERENCES AD_USUARIO (ID)');
            $this->addSql('ALTER TABLE AD_TIPO_DADO_PESSOAL ADD CONSTRAINT FK_C1741377A395BB94 FOREIGN KEY (APAGADO_POR) REFERENCES AD_USUARIO (ID)');
            $this->addSql('ALTER TABLE AD_TIPO_DADO_PESSOAL ADD CONSTRAINT FK_C1741377AF2B1A92 FOREIGN KEY (ATUALIZADO_POR) REFERENCES AD_USUARIO (ID)');
            $this->addSql('ALTER TABLE AD_TIPO_DADO_PESSOAL ADD CONSTRAINT FK_C1741377F69C7D9B FOREIGN KEY (CRIADO_POR) REFERENCES AD_USUARIO (ID)');
            $this->addSql('DROP TABLE ad_dossie');
            $this->addSql('DROP TABLE ad_tipo_dossie');
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_dossie DROP FOREIGN KEY FK_2F087C45DCF72A9');
            $this->addSql('CREATE TABLE ad_dado_pessoal (id INT AUTO_INCREMENT NOT NULL, tipo_dado_pessoal_id INT NOT NULL, pessoa_id INT NOT NULL, origem_dados_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, numero_documento_principal VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, conteudo JSON DEFAULT NULL, numero_identificador VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, protocolo_requerimento VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, status_req_pap_get VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, versao INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:guid)\', INDEX IDX_F2834A27DF6FA0A5 (pessoa_id), INDEX IDX_F2834A27F69C7D9B (criado_por), UNIQUE INDEX UNIQ_F2834A27D17F50A6 (uuid), INDEX IDX_F2834A27A395BB94 (apagado_por), INDEX IDX_F2834A27A654CBCD (origem_dados_id), INDEX IDX_F2834A27AF2B1A92 (atualizado_por), INDEX IDX_F2834A27D31FD11 (tipo_dado_pessoal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
            $this->addSql('CREATE TABLE ad_tipo_dado_pessoal (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, fonte_dados VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, descricao VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, sigla VARCHAR(25) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C1741377D17F50A6 (uuid), INDEX IDX_C1741377A395BB94 (apagado_por), INDEX IDX_C1741377AF2B1A92 (atualizado_por), INDEX IDX_C1741377F69C7D9B (criado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27A654CBCD FOREIGN KEY (origem_dados_id) REFERENCES ad_origem_dados (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27D31FD11 FOREIGN KEY (tipo_dado_pessoal_id) REFERENCES ad_tipo_dado_pessoal (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_dado_pessoal ADD CONSTRAINT FK_F2834A27A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_tipo_dado_pessoal ADD CONSTRAINT FK_C1741377AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_tipo_dado_pessoal ADD CONSTRAINT FK_C1741377F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_tipo_dado_pessoal ADD CONSTRAINT FK_C1741377A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('DROP TABLE ad_dossie');
            $this->addSql('DROP TABLE ad_tipo_dossie');
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
