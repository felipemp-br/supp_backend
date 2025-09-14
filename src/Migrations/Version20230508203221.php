<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508203221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.11.0 to 1.12.0';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE SEQUENCE ad_dados_formulario_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_formulario_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_mod_urn_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_ramo_direito_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_urn_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_vinc_metadados_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_vinc_org_cent_metadados_id_ START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_vinc_tese_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE ad_dados_formulario (id NUMBER(10) NOT NULL, formulario_id NUMBER(10) DEFAULT NULL NULL, componente_digital_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, data_value CLOB NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_F5822E7ED17F50A6 ON ad_dados_formulario (uuid)');
            $this->addSql('CREATE INDEX IDX_F5822E7E41CFE234 ON ad_dados_formulario (formulario_id)');
            $this->addSql('CREATE INDEX IDX_F5822E7E141B5D3A ON ad_dados_formulario (componente_digital_id)');
            $this->addSql('CREATE INDEX IDX_F5822E7EF69C7D9B ON ad_dados_formulario (criado_por)');
            $this->addSql('CREATE INDEX IDX_F5822E7EAF2B1A92 ON ad_dados_formulario (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_F5822E7EA395BB94 ON ad_dados_formulario (apagado_por)');
            $this->addSql('CREATE INDEX IDX_F5822E7EFF6A170EBF396750 ON ad_dados_formulario (apagado_em, id)');
            $this->addSql('CREATE TABLE ad_formulario (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, sigla VARCHAR2(255) NOT NULL, data_schema CLOB NOT NULL, template CLOB DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_FDC43C07D17F50A6 ON ad_formulario (uuid)');
            $this->addSql('CREATE INDEX IDX_FDC43C07F69C7D9B ON ad_formulario (criado_por)');
            $this->addSql('CREATE INDEX IDX_FDC43C07AF2B1A92 ON ad_formulario (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_FDC43C07A395BB94 ON ad_formulario (apagado_por)');
            $this->addSql('CREATE INDEX IDX_FDC43C07FF6A170EBF396750 ON ad_formulario (apagado_em, id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_FDC43C07801B7D4B ON ad_formulario (sigla)');
            $this->addSql('CREATE TABLE ad_mod_urn (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, valor VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_590095B9D17F50A6 ON ad_mod_urn (uuid)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_590095B92E892728 ON ad_mod_urn (valor)');
            $this->addSql('CREATE INDEX IDX_590095B9F69C7D9B ON ad_mod_urn (criado_por)');
            $this->addSql('CREATE INDEX IDX_590095B9AF2B1A92 ON ad_mod_urn (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_590095B9A395BB94 ON ad_mod_urn (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_590095B92E892728FF6A170E ON ad_mod_urn (valor, apagado_em)');
            $this->addSql('CREATE TABLE ad_ramo_direito (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, sigla VARCHAR2(25) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_EBC3BBB8D17F50A6 ON ad_ramo_direito (uuid)');
            $this->addSql('CREATE INDEX IDX_EBC3BBB8F69C7D9B ON ad_ramo_direito (criado_por)');
            $this->addSql('CREATE INDEX IDX_EBC3BBB8AF2B1A92 ON ad_ramo_direito (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_EBC3BBB8A395BB94 ON ad_ramo_direito (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_EBC3BBB854BD530CFF6A170E ON ad_ramo_direito (nome, apagado_em)');
            $this->addSql('CREATE TABLE ad_tema (id NUMBER(10) NOT NULL, ramo_direito_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, sigla VARCHAR2(25) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_D92CF75DD17F50A6 ON ad_tema (uuid)');
            $this->addSql('CREATE INDEX IDX_D92CF75D565FE38E ON ad_tema (ramo_direito_id)');
            $this->addSql('CREATE INDEX IDX_D92CF75DF69C7D9B ON ad_tema (criado_por)');
            $this->addSql('CREATE INDEX IDX_D92CF75DAF2B1A92 ON ad_tema (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_D92CF75DA395BB94 ON ad_tema (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_D92CF75D54BD530C565FE38EF ON ad_tema (nome, ramo_direito_id, apagado_em)');
            $this->addSql('CREATE TABLE ad_tese (id NUMBER(10) NOT NULL, tema_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, enunciado CLOB NOT NULL, ementa VARCHAR2(4000) NOT NULL, keywords VARCHAR2(255) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, sigla VARCHAR2(25) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_A000C9BD17F50A6 ON ad_tese (uuid)');
            $this->addSql('CREATE INDEX IDX_A000C9BA64A8A17 ON ad_tese (tema_id)');
            $this->addSql('CREATE INDEX IDX_A000C9BF69C7D9B ON ad_tese (criado_por)');
            $this->addSql('CREATE INDEX IDX_A000C9BAF2B1A92 ON ad_tese (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_A000C9BA395BB94 ON ad_tese (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_A000C9B54BD530CFF6A170E ON ad_tese (nome, apagado_em)');
            $this->addSql('CREATE TABLE ad_urn (id NUMBER(10) NOT NULL, mod_urn_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, titulo_dispositivo VARCHAR2(255) NOT NULL, urn VARCHAR2(255) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_688F4EB5D17F50A6 ON ad_urn (uuid)');
            $this->addSql('CREATE INDEX IDX_688F4EB55F712D13 ON ad_urn (mod_urn_id)');
            $this->addSql('CREATE INDEX IDX_688F4EB5F69C7D9B ON ad_urn (criado_por)');
            $this->addSql('CREATE INDEX IDX_688F4EB5AF2B1A92 ON ad_urn (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_688F4EB5A395BB94 ON ad_urn (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_688F4EB51A7824825F712D13F ON ad_urn (urn, mod_urn_id, apagado_em)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_688F4EB5839887795F712D13F ON ad_urn (titulo_dispositivo, mod_urn_id, apagado_em)');
            $this->addSql('CREATE TABLE ad_vinc_metadados (id NUMBER(10) NOT NULL, tese_id NUMBER(10) NOT NULL, urn_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, id_dispositivo VARCHAR2(255) DEFAULT NULL NULL, texto_dispositivo VARCHAR2(255) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_B47BA4E2D17F50A6 ON ad_vinc_metadados (uuid)');
            $this->addSql('CREATE INDEX IDX_B47BA4E2F6F834A3 ON ad_vinc_metadados (tese_id)');
            $this->addSql('CREATE INDEX IDX_B47BA4E22BC6126C ON ad_vinc_metadados (urn_id)');
            $this->addSql('CREATE INDEX IDX_B47BA4E2F69C7D9B ON ad_vinc_metadados (criado_por)');
            $this->addSql('CREATE INDEX IDX_B47BA4E2AF2B1A92 ON ad_vinc_metadados (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_B47BA4E2A395BB94 ON ad_vinc_metadados (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_B47BA4E2F6F834A32BC6126CB ON ad_vinc_metadados (tese_id, urn_id, id_dispositivo, apagado_em)');
            $this->addSql('CREATE TABLE ad_vinc_org_cent_metadados (id NUMBER(10) NOT NULL, tese_id NUMBER(10) NOT NULL, mod_orgao_central_id NUMBER(10) NOT NULL, documento_id NUMBER(10) DEFAULT NULL NULL, modelo_id NUMBER(10) DEFAULT NULL NULL, repositorio_id NUMBER(10) DEFAULT NULL NULL, assunto_administrativo_id NUMBER(10) DEFAULT NULL NULL, especie_setor_id NUMBER(10) DEFAULT NULL NULL, especie_processo_id NUMBER(10) DEFAULT NULL NULL, especie_documento_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_9D14218ED17F50A6 ON ad_vinc_org_cent_metadados (uuid)');
            $this->addSql('CREATE INDEX IDX_9D14218EF6F834A3 ON ad_vinc_org_cent_metadados (tese_id)');
            $this->addSql('CREATE INDEX IDX_9D14218EB7CB576B ON ad_vinc_org_cent_metadados (mod_orgao_central_id)');
            $this->addSql('CREATE INDEX IDX_9D14218E45C0CF75 ON ad_vinc_org_cent_metadados (documento_id)');
            $this->addSql('CREATE INDEX IDX_9D14218EC3A9576E ON ad_vinc_org_cent_metadados (modelo_id)');
            $this->addSql('CREATE INDEX IDX_9D14218EBED76CEB ON ad_vinc_org_cent_metadados (repositorio_id)');
            $this->addSql('CREATE INDEX IDX_9D14218E36BFEB50 ON ad_vinc_org_cent_metadados (assunto_administrativo_id)');
            $this->addSql('CREATE INDEX IDX_9D14218E87F70880 ON ad_vinc_org_cent_metadados (especie_setor_id)');
            $this->addSql('CREATE INDEX IDX_9D14218E669869DC ON ad_vinc_org_cent_metadados (especie_processo_id)');
            $this->addSql('CREATE INDEX IDX_9D14218EA2B4D239 ON ad_vinc_org_cent_metadados (especie_documento_id)');
            $this->addSql('CREATE INDEX IDX_9D14218EF69C7D9B ON ad_vinc_org_cent_metadados (criado_por)');
            $this->addSql('CREATE INDEX IDX_9D14218EAF2B1A92 ON ad_vinc_org_cent_metadados (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_9D14218EA395BB94 ON ad_vinc_org_cent_metadados (apagado_por)');
            $this->addSql('CREATE TABLE ad_vinc_tese (id NUMBER(10) NOT NULL, tese_id NUMBER(10) NOT NULL, processo_id NUMBER(10) DEFAULT NULL NULL, componente_digital_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_500C4C0CD17F50A6 ON ad_vinc_tese (uuid)');
            $this->addSql('CREATE INDEX IDX_500C4C0CF6F834A3 ON ad_vinc_tese (tese_id)');
            $this->addSql('CREATE INDEX IDX_500C4C0CAAA822D2 ON ad_vinc_tese (processo_id)');
            $this->addSql('CREATE INDEX IDX_500C4C0C141B5D3A ON ad_vinc_tese (componente_digital_id)');
            $this->addSql('CREATE INDEX IDX_500C4C0CF69C7D9B ON ad_vinc_tese (criado_por)');
            $this->addSql('CREATE INDEX IDX_500C4C0CAF2B1A92 ON ad_vinc_tese (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_500C4C0CA395BB94 ON ad_vinc_tese (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_9D14218EF6F834A3B7CB576B4 ON ad_vinc_org_cent_metadados (tese_id, mod_orgao_central_id, documento_id, modelo_id, repositorio_id, assunto_administrativo_id, especie_setor_id, especie_processo_id, especie_documento_id, apagado_em)');
            $this->addSql('ALTER TABLE ad_dados_formulario ADD CONSTRAINT FK_F5822E7E41CFE234 FOREIGN KEY (formulario_id) REFERENCES ad_formulario (id)');
            $this->addSql('ALTER TABLE ad_dados_formulario ADD CONSTRAINT FK_F5822E7E141B5D3A FOREIGN KEY (componente_digital_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('ALTER TABLE ad_dados_formulario ADD CONSTRAINT FK_F5822E7EF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dados_formulario ADD CONSTRAINT FK_F5822E7EAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dados_formulario ADD CONSTRAINT FK_F5822E7EA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_formulario ADD CONSTRAINT FK_FDC43C07F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_formulario ADD CONSTRAINT FK_FDC43C07AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_formulario ADD CONSTRAINT FK_FDC43C07A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_mod_urn ADD CONSTRAINT FK_590095B9F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_mod_urn ADD CONSTRAINT FK_590095B9AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_mod_urn ADD CONSTRAINT FK_590095B9A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_ramo_direito ADD CONSTRAINT FK_EBC3BBB8F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_ramo_direito ADD CONSTRAINT FK_EBC3BBB8AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_ramo_direito ADD CONSTRAINT FK_EBC3BBB8A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tema ADD CONSTRAINT FK_D92CF75D565FE38E FOREIGN KEY (ramo_direito_id) REFERENCES ad_ramo_direito (id)');
            $this->addSql('ALTER TABLE ad_tema ADD CONSTRAINT FK_D92CF75DF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tema ADD CONSTRAINT FK_D92CF75DAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tema ADD CONSTRAINT FK_D92CF75DA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tese ADD CONSTRAINT FK_A000C9BA64A8A17 FOREIGN KEY (tema_id) REFERENCES ad_tema (id)');
            $this->addSql('ALTER TABLE ad_tese ADD CONSTRAINT FK_A000C9BF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tese ADD CONSTRAINT FK_A000C9BAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tese ADD CONSTRAINT FK_A000C9BA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_urn ADD CONSTRAINT FK_688F4EB55F712D13 FOREIGN KEY (mod_urn_id) REFERENCES ad_mod_urn (id)');
            $this->addSql('ALTER TABLE ad_urn ADD CONSTRAINT FK_688F4EB5F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_urn ADD CONSTRAINT FK_688F4EB5AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_urn ADD CONSTRAINT FK_688F4EB5A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_metadados ADD CONSTRAINT FK_B47BA4E2F6F834A3 FOREIGN KEY (tese_id) REFERENCES ad_tese (id)');
            $this->addSql('ALTER TABLE ad_vinc_metadados ADD CONSTRAINT FK_B47BA4E22BC6126C FOREIGN KEY (urn_id) REFERENCES ad_urn (id)');
            $this->addSql('ALTER TABLE ad_vinc_metadados ADD CONSTRAINT FK_B47BA4E2F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_metadados ADD CONSTRAINT FK_B47BA4E2AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_metadados ADD CONSTRAINT FK_B47BA4E2A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EF6F834A3 FOREIGN KEY (tese_id) REFERENCES ad_tese (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EB7CB576B FOREIGN KEY (mod_orgao_central_id) REFERENCES ad_mod_orgao_central (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218E45C0CF75 FOREIGN KEY (documento_id) REFERENCES ad_documento (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EC3A9576E FOREIGN KEY (modelo_id) REFERENCES ad_modelo (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EBED76CEB FOREIGN KEY (repositorio_id) REFERENCES ad_repositorio (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218E36BFEB50 FOREIGN KEY (assunto_administrativo_id) REFERENCES ad_assunto_administrativo (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218E87F70880 FOREIGN KEY (especie_setor_id) REFERENCES ad_especie_setor (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218E669869DC FOREIGN KEY (especie_processo_id) REFERENCES ad_especie_processo (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EA2B4D239 FOREIGN KEY (especie_documento_id) REFERENCES ad_especie_documento (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_tese ADD CONSTRAINT FK_500C4C0CF6F834A3 FOREIGN KEY (tese_id) REFERENCES ad_tese (id)');
            $this->addSql('ALTER TABLE ad_vinc_tese ADD CONSTRAINT FK_500C4C0CAAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE ad_vinc_tese ADD CONSTRAINT FK_500C4C0C141B5D3A FOREIGN KEY (componente_digital_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('ALTER TABLE ad_vinc_tese ADD CONSTRAINT FK_500C4C0CF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_tese ADD CONSTRAINT FK_500C4C0CAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_tese ADD CONSTRAINT FK_500C4C0CA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE AD_CRONJOB ADD (num_jobs_pendentes NUMBER(10) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_HISTORICO MODIFY (processo_id NUMBER(10) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE ad_componente_digital ADD (json CLOB DEFAULT NULL NULL, sumarizacao VARCHAR2(4000) DEFAULT NULL NULL)');

        } else {
        // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE TABLE ad_dados_formulario (id INT AUTO_INCREMENT NOT NULL, formulario_id INT DEFAULT NULL, componente_digital_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, data_value LONGTEXT NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_F5822E7ED17F50A6 (uuid), INDEX IDX_F5822E7E41CFE234 (formulario_id), INDEX IDX_F5822E7E141B5D3A (componente_digital_id), INDEX IDX_F5822E7EF69C7D9B (criado_por), INDEX IDX_F5822E7EAF2B1A92 (atualizado_por), INDEX IDX_F5822E7EA395BB94 (apagado_por), INDEX IDX_F5822E7EFF6A170EBF396750 (apagado_em, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_formulario (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, sigla VARCHAR(255) NOT NULL, data_schema LONGTEXT NOT NULL, template LONGTEXT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_FDC43C07D17F50A6 (uuid), INDEX IDX_FDC43C07F69C7D9B (criado_por), INDEX IDX_FDC43C07AF2B1A92 (atualizado_por), INDEX IDX_FDC43C07A395BB94 (apagado_por), INDEX IDX_FDC43C07FF6A170EBF396750 (apagado_em, id), UNIQUE INDEX UNIQ_FDC43C07801B7D4B (sigla), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_mod_urn (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', valor VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_590095B9D17F50A6 (uuid), UNIQUE INDEX UNIQ_590095B92E892728 (valor), INDEX IDX_590095B9F69C7D9B (criado_por), INDEX IDX_590095B9AF2B1A92 (atualizado_por), INDEX IDX_590095B9A395BB94 (apagado_por), UNIQUE INDEX UNIQ_590095B92E892728FF6A170E (valor, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_ramo_direito (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) NOT NULL, sigla VARCHAR(25) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_EBC3BBB8D17F50A6 (uuid), INDEX IDX_EBC3BBB8F69C7D9B (criado_por), INDEX IDX_EBC3BBB8AF2B1A92 (atualizado_por), INDEX IDX_EBC3BBB8A395BB94 (apagado_por), UNIQUE INDEX UNIQ_EBC3BBB854BD530CFF6A170E (nome, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_tema (id INT AUTO_INCREMENT NOT NULL, ramo_direito_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) NOT NULL, sigla VARCHAR(25) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D92CF75DD17F50A6 (uuid), INDEX IDX_D92CF75D565FE38E (ramo_direito_id), INDEX IDX_D92CF75DF69C7D9B (criado_por), INDEX IDX_D92CF75DAF2B1A92 (atualizado_por), INDEX IDX_D92CF75DA395BB94 (apagado_por), UNIQUE INDEX UNIQ_D92CF75D54BD530C565FE38EFF6A170E (nome, ramo_direito_id, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_tese (id INT AUTO_INCREMENT NOT NULL, tema_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, enunciado LONGTEXT NOT NULL, ementa VARCHAR(4000) NOT NULL, keywords VARCHAR(255) NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) NOT NULL, sigla VARCHAR(25) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A000C9BD17F50A6 (uuid), INDEX IDX_A000C9BA64A8A17 (tema_id), INDEX IDX_A000C9BF69C7D9B (criado_por), INDEX IDX_A000C9BAF2B1A92 (atualizado_por), INDEX IDX_A000C9BA395BB94 (apagado_por), UNIQUE INDEX UNIQ_A000C9B54BD530CFF6A170E (nome, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_urn (id INT AUTO_INCREMENT NOT NULL, mod_urn_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, titulo_dispositivo VARCHAR(255) NOT NULL, urn VARCHAR(255) NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_688F4EB5D17F50A6 (uuid), INDEX IDX_688F4EB55F712D13 (mod_urn_id), INDEX IDX_688F4EB5F69C7D9B (criado_por), INDEX IDX_688F4EB5AF2B1A92 (atualizado_por), INDEX IDX_688F4EB5A395BB94 (apagado_por), UNIQUE INDEX UNIQ_688F4EB51A7824825F712D13FF6A170E (urn, mod_urn_id, apagado_em), UNIQUE INDEX UNIQ_688F4EB5839887795F712D13FF6A170E (titulo_dispositivo, mod_urn_id, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_vinc_metadados (id INT AUTO_INCREMENT NOT NULL, tese_id INT NOT NULL, urn_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, id_dispositivo VARCHAR(255) DEFAULT NULL, texto_dispositivo VARCHAR(255) DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_B47BA4E2D17F50A6 (uuid), INDEX IDX_B47BA4E2F6F834A3 (tese_id), INDEX IDX_B47BA4E22BC6126C (urn_id), INDEX IDX_B47BA4E2F69C7D9B (criado_por), INDEX IDX_B47BA4E2AF2B1A92 (atualizado_por), INDEX IDX_B47BA4E2A395BB94 (apagado_por), UNIQUE INDEX UNIQ_B47BA4E2F6F834A32BC6126CB84DEB19FF6A170E (tese_id, urn_id, id_dispositivo, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_vinc_org_cent_metadados (id INT AUTO_INCREMENT NOT NULL, tese_id INT NOT NULL, mod_orgao_central_id INT NOT NULL, documento_id INT DEFAULT NULL, modelo_id INT DEFAULT NULL, repositorio_id INT DEFAULT NULL, assunto_administrativo_id INT DEFAULT NULL, especie_setor_id INT DEFAULT NULL, especie_processo_id INT DEFAULT NULL, especie_documento_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_9D14218ED17F50A6 (uuid), INDEX IDX_9D14218EF6F834A3 (tese_id), INDEX IDX_9D14218EB7CB576B (mod_orgao_central_id), INDEX IDX_9D14218E45C0CF75 (documento_id), INDEX IDX_9D14218EC3A9576E (modelo_id), INDEX IDX_9D14218EBED76CEB (repositorio_id), INDEX IDX_9D14218E36BFEB50 (assunto_administrativo_id), INDEX IDX_9D14218E87F70880 (especie_setor_id), INDEX IDX_9D14218E669869DC (especie_processo_id), INDEX IDX_9D14218EA2B4D239 (especie_documento_id), INDEX IDX_9D14218EF69C7D9B (criado_por), INDEX IDX_9D14218EAF2B1A92 (atualizado_por), INDEX IDX_9D14218EA395BB94 (apagado_por), UNIQUE INDEX UNIQ_9D14218EF6F834A3B7CB576B45C0CF75C3A9576EBED76CEB36BFEB5087 (tese_id, mod_orgao_central_id, documento_id, modelo_id, repositorio_id, assunto_administrativo_id, especie_setor_id, especie_processo_id, especie_documento_id, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_vinc_tese (id INT AUTO_INCREMENT NOT NULL, tese_id INT NOT NULL, processo_id INT DEFAULT NULL, componente_digital_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_500C4C0CD17F50A6 (uuid), INDEX IDX_500C4C0CF6F834A3 (tese_id), INDEX IDX_500C4C0CAAA822D2 (processo_id), INDEX IDX_500C4C0C141B5D3A (componente_digital_id), INDEX IDX_500C4C0CF69C7D9B (criado_por), INDEX IDX_500C4C0CAF2B1A92 (atualizado_por), INDEX IDX_500C4C0CA395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE ad_dados_formulario ADD CONSTRAINT FK_F5822E7E41CFE234 FOREIGN KEY (formulario_id) REFERENCES ad_formulario (id)');
            $this->addSql('ALTER TABLE ad_dados_formulario ADD CONSTRAINT FK_F5822E7E141B5D3A FOREIGN KEY (componente_digital_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('ALTER TABLE ad_dados_formulario ADD CONSTRAINT FK_F5822E7EF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dados_formulario ADD CONSTRAINT FK_F5822E7EAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_dados_formulario ADD CONSTRAINT FK_F5822E7EA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_formulario ADD CONSTRAINT FK_FDC43C07F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_formulario ADD CONSTRAINT FK_FDC43C07AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_formulario ADD CONSTRAINT FK_FDC43C07A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_mod_urn ADD CONSTRAINT FK_590095B9F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_mod_urn ADD CONSTRAINT FK_590095B9AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_mod_urn ADD CONSTRAINT FK_590095B9A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_ramo_direito ADD CONSTRAINT FK_EBC3BBB8F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_ramo_direito ADD CONSTRAINT FK_EBC3BBB8AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_ramo_direito ADD CONSTRAINT FK_EBC3BBB8A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tema ADD CONSTRAINT FK_D92CF75D565FE38E FOREIGN KEY (ramo_direito_id) REFERENCES ad_ramo_direito (id)');
            $this->addSql('ALTER TABLE ad_tema ADD CONSTRAINT FK_D92CF75DF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tema ADD CONSTRAINT FK_D92CF75DAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tema ADD CONSTRAINT FK_D92CF75DA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tese ADD CONSTRAINT FK_A000C9BA64A8A17 FOREIGN KEY (tema_id) REFERENCES ad_tema (id)');
            $this->addSql('ALTER TABLE ad_tese ADD CONSTRAINT FK_A000C9BF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tese ADD CONSTRAINT FK_A000C9BAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_tese ADD CONSTRAINT FK_A000C9BA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_urn ADD CONSTRAINT FK_688F4EB55F712D13 FOREIGN KEY (mod_urn_id) REFERENCES ad_mod_urn (id)');
            $this->addSql('ALTER TABLE ad_urn ADD CONSTRAINT FK_688F4EB5F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_urn ADD CONSTRAINT FK_688F4EB5AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_urn ADD CONSTRAINT FK_688F4EB5A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_metadados ADD CONSTRAINT FK_B47BA4E2F6F834A3 FOREIGN KEY (tese_id) REFERENCES ad_tese (id)');
            $this->addSql('ALTER TABLE ad_vinc_metadados ADD CONSTRAINT FK_B47BA4E22BC6126C FOREIGN KEY (urn_id) REFERENCES ad_urn (id)');
            $this->addSql('ALTER TABLE ad_vinc_metadados ADD CONSTRAINT FK_B47BA4E2F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_metadados ADD CONSTRAINT FK_B47BA4E2AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_metadados ADD CONSTRAINT FK_B47BA4E2A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EF6F834A3 FOREIGN KEY (tese_id) REFERENCES ad_tese (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EB7CB576B FOREIGN KEY (mod_orgao_central_id) REFERENCES ad_mod_orgao_central (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218E45C0CF75 FOREIGN KEY (documento_id) REFERENCES ad_documento (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EC3A9576E FOREIGN KEY (modelo_id) REFERENCES ad_modelo (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EBED76CEB FOREIGN KEY (repositorio_id) REFERENCES ad_repositorio (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218E36BFEB50 FOREIGN KEY (assunto_administrativo_id) REFERENCES ad_assunto_administrativo (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218E87F70880 FOREIGN KEY (especie_setor_id) REFERENCES ad_especie_setor (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218E669869DC FOREIGN KEY (especie_processo_id) REFERENCES ad_especie_processo (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EA2B4D239 FOREIGN KEY (especie_documento_id) REFERENCES ad_especie_documento (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados ADD CONSTRAINT FK_9D14218EA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_tese ADD CONSTRAINT FK_500C4C0CF6F834A3 FOREIGN KEY (tese_id) REFERENCES ad_tese (id)');
            $this->addSql('ALTER TABLE ad_vinc_tese ADD CONSTRAINT FK_500C4C0CAAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE ad_vinc_tese ADD CONSTRAINT FK_500C4C0C141B5D3A FOREIGN KEY (componente_digital_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('ALTER TABLE ad_vinc_tese ADD CONSTRAINT FK_500C4C0CF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_tese ADD CONSTRAINT FK_500C4C0CAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_tese ADD CONSTRAINT FK_500C4C0CA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_cronjob ADD num_jobs_pendentes INT DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_historico CHANGE processo_id processo_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_componente_digital ADD sumarizacao VARCHAR(4000) DEFAULT NULL, ADD json LONGTEXT DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('DROP SEQUENCE ad_dados_formulario_id_seq');
            $this->addSql('DROP SEQUENCE ad_formulario_id_seq');
            $this->addSql('DROP SEQUENCE ad_mod_urn_id_seq');
            $this->addSql('DROP SEQUENCE ad_ramo_direito_id_seq');
            $this->addSql('DROP SEQUENCE ad_urn_id_seq');
            $this->addSql('DROP SEQUENCE ad_vinc_metadados_id_seq');
            $this->addSql('DROP SEQUENCE ad_vinc_org_cent_metadados_id_');
            $this->addSql('ALTER TABLE ad_dados_formulario DROP CONSTRAINT FK_F5822E7E41CFE234');
            $this->addSql('ALTER TABLE ad_dados_formulario DROP CONSTRAINT FK_F5822E7E141B5D3A');
            $this->addSql('ALTER TABLE ad_dados_formulario DROP CONSTRAINT FK_F5822E7EF69C7D9B');
            $this->addSql('ALTER TABLE ad_dados_formulario DROP CONSTRAINT FK_F5822E7EAF2B1A92');
            $this->addSql('ALTER TABLE ad_dados_formulario DROP CONSTRAINT FK_F5822E7EA395BB94');
            $this->addSql('ALTER TABLE ad_formulario DROP CONSTRAINT FK_FDC43C07F69C7D9B');
            $this->addSql('ALTER TABLE ad_formulario DROP CONSTRAINT FK_FDC43C07AF2B1A92');
            $this->addSql('ALTER TABLE ad_formulario DROP CONSTRAINT FK_FDC43C07A395BB94');
            $this->addSql('ALTER TABLE ad_mod_urn DROP CONSTRAINT FK_590095B9F69C7D9B');
            $this->addSql('ALTER TABLE ad_mod_urn DROP CONSTRAINT FK_590095B9AF2B1A92');
            $this->addSql('ALTER TABLE ad_mod_urn DROP CONSTRAINT FK_590095B9A395BB94');
            $this->addSql('ALTER TABLE ad_ramo_direito DROP CONSTRAINT FK_EBC3BBB8F69C7D9B');
            $this->addSql('ALTER TABLE ad_ramo_direito DROP CONSTRAINT FK_EBC3BBB8AF2B1A92');
            $this->addSql('ALTER TABLE ad_ramo_direito DROP CONSTRAINT FK_EBC3BBB8A395BB94');
            $this->addSql('ALTER TABLE ad_tema DROP CONSTRAINT FK_D92CF75D565FE38E');
            $this->addSql('ALTER TABLE ad_tema DROP CONSTRAINT FK_D92CF75DF69C7D9B');
            $this->addSql('ALTER TABLE ad_tema DROP CONSTRAINT FK_D92CF75DAF2B1A92');
            $this->addSql('ALTER TABLE ad_tema DROP CONSTRAINT FK_D92CF75DA395BB94');
            $this->addSql('ALTER TABLE ad_tese DROP CONSTRAINT FK_A000C9BA64A8A17');
            $this->addSql('ALTER TABLE ad_tese DROP CONSTRAINT FK_A000C9BF69C7D9B');
            $this->addSql('ALTER TABLE ad_tese DROP CONSTRAINT FK_A000C9BAF2B1A92');
            $this->addSql('ALTER TABLE ad_tese DROP CONSTRAINT FK_A000C9BA395BB94');
            $this->addSql('ALTER TABLE ad_urn DROP CONSTRAINT FK_688F4EB55F712D13');
            $this->addSql('ALTER TABLE ad_urn DROP CONSTRAINT FK_688F4EB5F69C7D9B');
            $this->addSql('ALTER TABLE ad_urn DROP CONSTRAINT FK_688F4EB5AF2B1A92');
            $this->addSql('ALTER TABLE ad_urn DROP CONSTRAINT FK_688F4EB5A395BB94');
            $this->addSql('ALTER TABLE ad_vinc_metadados DROP CONSTRAINT FK_B47BA4E2F6F834A3');
            $this->addSql('ALTER TABLE ad_vinc_metadados DROP CONSTRAINT FK_B47BA4E22BC6126C');
            $this->addSql('ALTER TABLE ad_vinc_metadados DROP CONSTRAINT FK_B47BA4E2F69C7D9B');
            $this->addSql('ALTER TABLE ad_vinc_metadados DROP CONSTRAINT FK_B47BA4E2AF2B1A92');
            $this->addSql('ALTER TABLE ad_vinc_metadados DROP CONSTRAINT FK_B47BA4E2A395BB94');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP CONSTRAINT FK_9D14218EF6F834A3');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP CONSTRAINT FK_9D14218EB7CB576B');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP CONSTRAINT FK_9D14218E45C0CF75');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP CONSTRAINT FK_9D14218EC3A9576E');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP CONSTRAINT FK_9D14218EBED76CEB');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP CONSTRAINT FK_9D14218E36BFEB50');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP CONSTRAINT FK_9D14218E87F70880');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP CONSTRAINT FK_9D14218E669869DC');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP CONSTRAINT FK_9D14218EA2B4D239');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP CONSTRAINT FK_9D14218EF69C7D9B');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP CONSTRAINT FK_9D14218EAF2B1A92');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP CONSTRAINT FK_9D14218EA395BB94');
            $this->addSql('ALTER TABLE ad_vinc_tese DROP CONSTRAINT FK_500C4C0CF6F834A3');
            $this->addSql('ALTER TABLE ad_vinc_tese DROP CONSTRAINT FK_500C4C0CAAA822D2');
            $this->addSql('ALTER TABLE ad_vinc_tese DROP CONSTRAINT FK_500C4C0C141B5D3A');
            $this->addSql('ALTER TABLE ad_vinc_tese DROP CONSTRAINT FK_500C4C0CF69C7D9B');
            $this->addSql('ALTER TABLE ad_vinc_tese DROP CONSTRAINT FK_500C4C0CAF2B1A92');
            $this->addSql('ALTER TABLE ad_vinc_tese DROP CONSTRAINT FK_500C4C0CA395BB94');
            $this->addSql('DROP TABLE ad_dados_formulario');
            $this->addSql('DROP TABLE ad_formulario');
            $this->addSql('DROP TABLE ad_mod_urn');
            $this->addSql('DROP TABLE ad_ramo_direito');
            $this->addSql('DROP TABLE ad_tema');
            $this->addSql('DROP TABLE ad_tese');
            $this->addSql('DROP TABLE ad_urn');
            $this->addSql('DROP TABLE ad_vinc_metadados');
            $this->addSql('DROP TABLE ad_vinc_org_cent_metadados');
            $this->addSql('DROP TABLE ad_vinc_tese');
            $this->addSql('ALTER TABLE ad_cronjob DROP (num_jobs_pendentes)');
            $this->addSql('ALTER TABLE ad_historico MODIFY (PROCESSO_ID NUMBER(10) NOT NULL)');
            $this->addSql('ALTER TABLE ad_componente_digital DROP (sumarizacao, json)');
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_dados_formulario DROP FOREIGN KEY FK_F5822E7E41CFE234');
            $this->addSql('ALTER TABLE ad_dados_formulario DROP FOREIGN KEY FK_F5822E7E141B5D3A');
            $this->addSql('ALTER TABLE ad_dados_formulario DROP FOREIGN KEY FK_F5822E7EF69C7D9B');
            $this->addSql('ALTER TABLE ad_dados_formulario DROP FOREIGN KEY FK_F5822E7EAF2B1A92');
            $this->addSql('ALTER TABLE ad_dados_formulario DROP FOREIGN KEY FK_F5822E7EA395BB94');
            $this->addSql('ALTER TABLE ad_formulario DROP FOREIGN KEY FK_FDC43C07F69C7D9B');
            $this->addSql('ALTER TABLE ad_formulario DROP FOREIGN KEY FK_FDC43C07AF2B1A92');
            $this->addSql('ALTER TABLE ad_formulario DROP FOREIGN KEY FK_FDC43C07A395BB94');
            $this->addSql('ALTER TABLE ad_mod_urn DROP FOREIGN KEY FK_590095B9F69C7D9B');
            $this->addSql('ALTER TABLE ad_mod_urn DROP FOREIGN KEY FK_590095B9AF2B1A92');
            $this->addSql('ALTER TABLE ad_mod_urn DROP FOREIGN KEY FK_590095B9A395BB94');
            $this->addSql('ALTER TABLE ad_ramo_direito DROP FOREIGN KEY FK_EBC3BBB8F69C7D9B');
            $this->addSql('ALTER TABLE ad_ramo_direito DROP FOREIGN KEY FK_EBC3BBB8AF2B1A92');
            $this->addSql('ALTER TABLE ad_ramo_direito DROP FOREIGN KEY FK_EBC3BBB8A395BB94');
            $this->addSql('ALTER TABLE ad_tema DROP FOREIGN KEY FK_D92CF75D565FE38E');
            $this->addSql('ALTER TABLE ad_tema DROP FOREIGN KEY FK_D92CF75DF69C7D9B');
            $this->addSql('ALTER TABLE ad_tema DROP FOREIGN KEY FK_D92CF75DAF2B1A92');
            $this->addSql('ALTER TABLE ad_tema DROP FOREIGN KEY FK_D92CF75DA395BB94');
            $this->addSql('ALTER TABLE ad_tese DROP FOREIGN KEY FK_A000C9BA64A8A17');
            $this->addSql('ALTER TABLE ad_tese DROP FOREIGN KEY FK_A000C9BF69C7D9B');
            $this->addSql('ALTER TABLE ad_tese DROP FOREIGN KEY FK_A000C9BAF2B1A92');
            $this->addSql('ALTER TABLE ad_tese DROP FOREIGN KEY FK_A000C9BA395BB94');
            $this->addSql('ALTER TABLE ad_urn DROP FOREIGN KEY FK_688F4EB55F712D13');
            $this->addSql('ALTER TABLE ad_urn DROP FOREIGN KEY FK_688F4EB5F69C7D9B');
            $this->addSql('ALTER TABLE ad_urn DROP FOREIGN KEY FK_688F4EB5AF2B1A92');
            $this->addSql('ALTER TABLE ad_urn DROP FOREIGN KEY FK_688F4EB5A395BB94');
            $this->addSql('ALTER TABLE ad_vinc_metadados DROP FOREIGN KEY FK_B47BA4E2F6F834A3');
            $this->addSql('ALTER TABLE ad_vinc_metadados DROP FOREIGN KEY FK_B47BA4E22BC6126C');
            $this->addSql('ALTER TABLE ad_vinc_metadados DROP FOREIGN KEY FK_B47BA4E2F69C7D9B');
            $this->addSql('ALTER TABLE ad_vinc_metadados DROP FOREIGN KEY FK_B47BA4E2AF2B1A92');
            $this->addSql('ALTER TABLE ad_vinc_metadados DROP FOREIGN KEY FK_B47BA4E2A395BB94');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP FOREIGN KEY FK_9D14218EF6F834A3');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP FOREIGN KEY FK_9D14218EB7CB576B');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP FOREIGN KEY FK_9D14218E45C0CF75');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP FOREIGN KEY FK_9D14218EC3A9576E');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP FOREIGN KEY FK_9D14218EBED76CEB');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP FOREIGN KEY FK_9D14218E36BFEB50');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP FOREIGN KEY FK_9D14218E87F70880');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP FOREIGN KEY FK_9D14218E669869DC');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP FOREIGN KEY FK_9D14218EA2B4D239');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP FOREIGN KEY FK_9D14218EF69C7D9B');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP FOREIGN KEY FK_9D14218EAF2B1A92');
            $this->addSql('ALTER TABLE ad_vinc_org_cent_metadados DROP FOREIGN KEY FK_9D14218EA395BB94');
            $this->addSql('ALTER TABLE ad_vinc_tese DROP FOREIGN KEY FK_500C4C0CF6F834A3');
            $this->addSql('ALTER TABLE ad_vinc_tese DROP FOREIGN KEY FK_500C4C0CAAA822D2');
            $this->addSql('ALTER TABLE ad_vinc_tese DROP FOREIGN KEY FK_500C4C0C141B5D3A');
            $this->addSql('ALTER TABLE ad_vinc_tese DROP FOREIGN KEY FK_500C4C0CF69C7D9B');
            $this->addSql('ALTER TABLE ad_vinc_tese DROP FOREIGN KEY FK_500C4C0CAF2B1A92');
            $this->addSql('ALTER TABLE ad_vinc_tese DROP FOREIGN KEY FK_500C4C0CA395BB94');
            $this->addSql('DROP TABLE ad_dados_formulario');
            $this->addSql('DROP TABLE ad_formulario');
            $this->addSql('DROP TABLE ad_mod_urn');
            $this->addSql('DROP TABLE ad_ramo_direito');
            $this->addSql('DROP TABLE ad_tema');
            $this->addSql('DROP TABLE ad_tese');
            $this->addSql('DROP TABLE ad_urn');
            $this->addSql('DROP TABLE ad_vinc_metadados');
            $this->addSql('DROP TABLE ad_vinc_org_cent_metadados');
            $this->addSql('DROP TABLE ad_vinc_tese');
            $this->addSql('ALTER TABLE ad_cronjob DROP num_jobs_pendentes');
            $this->addSql('ALTER TABLE ad_historico CHANGE processo_id processo_id INT NOT NULL');
            $this->addSql('ALTER TABLE ad_componente_digital DROP sumarizacao, DROP json');
        }
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
