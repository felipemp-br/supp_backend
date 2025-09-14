<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220125192823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.7.0 do 1.8.0';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE AD_GARANTIA DROP CONSTRAINT FK_9CE60FE173533CF6');
            $this->addSql('DROP SEQUENCE AD_GARANTIA_ID_SEQ');
            $this->addSql('DROP SEQUENCE AD_MOD_GARANTIA_ID_SEQ');
            $this->addSql('CREATE SEQUENCE ad_avaliacao_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_bookmark_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_mod_copia_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_objeto_avaliado_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_vinc_esp_proc_workflow_id_s START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_vinc_trans_workflow_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_vinc_workflow_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE ad_avaliacao (id NUMBER(10) NOT NULL, objeto_avaliado_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, avaliacao NUMBER(10) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_EF228DFBD17F50A6 ON ad_avaliacao (uuid)');
            $this->addSql('CREATE INDEX IDX_EF228DFB14B23D30 ON ad_avaliacao (objeto_avaliado_id)');
            $this->addSql('CREATE INDEX IDX_EF228DFBF69C7D9B ON ad_avaliacao (criado_por)');
            $this->addSql('CREATE INDEX IDX_EF228DFBAF2B1A92 ON ad_avaliacao (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_EF228DFBA395BB94 ON ad_avaliacao (apagado_por)');
            $this->addSql('CREATE TABLE ad_bookmark (id NUMBER(10) NOT NULL, usuario_id NUMBER(10) NOT NULL, componente_digital_id NUMBER(10) NOT NULL, processo_id NUMBER(10) NOT NULL, juntada_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) DEFAULT NULL NULL, pagina NUMBER(10) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_307A9FCDD17F50A6 ON ad_bookmark (uuid)');
            $this->addSql('CREATE INDEX IDX_307A9FCDF7BDBDD2 ON ad_bookmark (juntada_id)');
            $this->addSql('CREATE INDEX IDX_307A9FCDF69C7D9B ON ad_bookmark (criado_por)');
            $this->addSql('CREATE INDEX IDX_307A9FCDAF2B1A92 ON ad_bookmark (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_307A9FCDA395BB94 ON ad_bookmark (apagado_por)');
            $this->addSql('CREATE INDEX usuario_id ON ad_bookmark (usuario_id)');
            $this->addSql('CREATE INDEX processo_id ON ad_bookmark (processo_id)');
            $this->addSql('CREATE INDEX componente_digital_id ON ad_bookmark (componente_digital_id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_307A9FCDDB38439EAAA822D21 ON ad_bookmark (usuario_id, processo_id, componente_digital_id, pagina, apagado_em)');
            $this->addSql('CREATE TABLE ad_mod_copia (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, valor VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_215ADBFFD17F50A6 ON ad_mod_copia (uuid)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_215ADBFF2E892728 ON ad_mod_copia (valor)');
            $this->addSql('CREATE INDEX IDX_215ADBFFF69C7D9B ON ad_mod_copia (criado_por)');
            $this->addSql('CREATE INDEX IDX_215ADBFFAF2B1A92 ON ad_mod_copia (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_215ADBFFA395BB94 ON ad_mod_copia (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_215ADBFF2E892728FF6A170E ON ad_mod_copia (valor, apagado_em)');
            $this->addSql('CREATE TABLE ad_objeto_avaliado (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, classe VARCHAR2(255) NOT NULL, objeto_id NUMBER(10) NOT NULL, avaliacao_resultante DOUBLE PRECISION DEFAULT NULL NULL, dt_ult_avaliacao TIMESTAMP(0) DEFAULT NULL NULL, qtd_avaliacoes NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_CA4C3CA1D17F50A6 ON ad_objeto_avaliado (uuid)');
            $this->addSql('CREATE INDEX IDX_CA4C3CA1F69C7D9B ON ad_objeto_avaliado (criado_por)');
            $this->addSql('CREATE INDEX IDX_CA4C3CA1AF2B1A92 ON ad_objeto_avaliado (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_CA4C3CA1A395BB94 ON ad_objeto_avaliado (apagado_por)');
            $this->addSql('CREATE INDEX idx_objeto_classe ON ad_objeto_avaliado (classe)');
            $this->addSql('CREATE INDEX idx_objeto_id ON ad_objeto_avaliado (objeto_id)');
            $this->addSql('CREATE TABLE ad_vinc_esp_proc_workflow (id NUMBER(10) NOT NULL, especie_processo_id NUMBER(10) NOT NULL, workflow_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_7801972FD17F50A6 ON ad_vinc_esp_proc_workflow (uuid)');
            $this->addSql('CREATE INDEX IDX_7801972F669869DC ON ad_vinc_esp_proc_workflow (especie_processo_id)');
            $this->addSql('CREATE INDEX IDX_7801972F2C7C2CBA ON ad_vinc_esp_proc_workflow (workflow_id)');
            $this->addSql('CREATE INDEX IDX_7801972FF69C7D9B ON ad_vinc_esp_proc_workflow (criado_por)');
            $this->addSql('CREATE INDEX IDX_7801972FAF2B1A92 ON ad_vinc_esp_proc_workflow (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_7801972FA395BB94 ON ad_vinc_esp_proc_workflow (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_7801972F2C7C2CBA669869DCF ON ad_vinc_esp_proc_workflow (workflow_id, especie_processo_id, apagado_em)');
            $this->addSql('CREATE TABLE ad_vinc_trans_workflow (id NUMBER(10) NOT NULL, transicao_workflow_id NUMBER(10) NOT NULL, workflow_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_F6E27F5D17F50A6 ON ad_vinc_trans_workflow (uuid)');
            $this->addSql('CREATE INDEX IDX_F6E27F58DA656D7 ON ad_vinc_trans_workflow (transicao_workflow_id)');
            $this->addSql('CREATE INDEX IDX_F6E27F52C7C2CBA ON ad_vinc_trans_workflow (workflow_id)');
            $this->addSql('CREATE INDEX IDX_F6E27F5F69C7D9B ON ad_vinc_trans_workflow (criado_por)');
            $this->addSql('CREATE INDEX IDX_F6E27F5AF2B1A92 ON ad_vinc_trans_workflow (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_F6E27F5A395BB94 ON ad_vinc_trans_workflow (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_F6E27F52C7C2CBA8DA656D7FF ON ad_vinc_trans_workflow (workflow_id, transicao_workflow_id, apagado_em)');
            $this->addSql('CREATE TABLE ad_vinc_workflow (id NUMBER(10) NOT NULL, tarefa_inicial_id NUMBER(10) NOT NULL, tarefa_atual_id NUMBER(10) NOT NULL, workflow_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, concluido NUMBER(1) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_F8DB36FDD17F50A6 ON ad_vinc_workflow (uuid)');
            $this->addSql('CREATE INDEX IDX_F8DB36FDB6894EFA ON ad_vinc_workflow (tarefa_inicial_id)');
            $this->addSql('CREATE INDEX IDX_F8DB36FD18FC7F39 ON ad_vinc_workflow (tarefa_atual_id)');
            $this->addSql('CREATE INDEX IDX_F8DB36FD2C7C2CBA ON ad_vinc_workflow (workflow_id)');
            $this->addSql('CREATE INDEX IDX_F8DB36FDF69C7D9B ON ad_vinc_workflow (criado_por)');
            $this->addSql('CREATE INDEX IDX_F8DB36FDAF2B1A92 ON ad_vinc_workflow (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_F8DB36FDA395BB94 ON ad_vinc_workflow (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_F8DB36FD2C7C2CBAB6894EFAF ON ad_vinc_workflow (workflow_id, tarefa_inicial_id, apagado_em)');
            $this->addSql('ALTER TABLE ad_avaliacao ADD CONSTRAINT FK_EF228DFB14B23D30 FOREIGN KEY (objeto_avaliado_id) REFERENCES ad_objeto_avaliado (id)');
            $this->addSql('ALTER TABLE ad_avaliacao ADD CONSTRAINT FK_EF228DFBF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_avaliacao ADD CONSTRAINT FK_EF228DFBAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_avaliacao ADD CONSTRAINT FK_EF228DFBA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCDDB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCD141B5D3A FOREIGN KEY (componente_digital_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCDAAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCDF7BDBDD2 FOREIGN KEY (juntada_id) REFERENCES ad_juntada (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCDF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCDAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCDA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_mod_copia ADD CONSTRAINT FK_215ADBFFF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_mod_copia ADD CONSTRAINT FK_215ADBFFAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_mod_copia ADD CONSTRAINT FK_215ADBFFA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_objeto_avaliado ADD CONSTRAINT FK_CA4C3CA1F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_objeto_avaliado ADD CONSTRAINT FK_CA4C3CA1AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_objeto_avaliado ADD CONSTRAINT FK_CA4C3CA1A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_esp_proc_workflow ADD CONSTRAINT FK_7801972F669869DC FOREIGN KEY (especie_processo_id) REFERENCES ad_especie_processo (id)');
            $this->addSql('ALTER TABLE ad_vinc_esp_proc_workflow ADD CONSTRAINT FK_7801972F2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES ad_workflow (id)');
            $this->addSql('ALTER TABLE ad_vinc_esp_proc_workflow ADD CONSTRAINT FK_7801972FF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_esp_proc_workflow ADD CONSTRAINT FK_7801972FAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_esp_proc_workflow ADD CONSTRAINT FK_7801972FA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_trans_workflow ADD CONSTRAINT FK_F6E27F58DA656D7 FOREIGN KEY (transicao_workflow_id) REFERENCES ad_transicao_workflow (id)');
            $this->addSql('ALTER TABLE ad_vinc_trans_workflow ADD CONSTRAINT FK_F6E27F52C7C2CBA FOREIGN KEY (workflow_id) REFERENCES ad_workflow (id)');
            $this->addSql('ALTER TABLE ad_vinc_trans_workflow ADD CONSTRAINT FK_F6E27F5F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_trans_workflow ADD CONSTRAINT FK_F6E27F5AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_trans_workflow ADD CONSTRAINT FK_F6E27F5A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_workflow ADD CONSTRAINT FK_F8DB36FDB6894EFA FOREIGN KEY (tarefa_inicial_id) REFERENCES ad_tarefa (id)');
            $this->addSql('ALTER TABLE ad_vinc_workflow ADD CONSTRAINT FK_F8DB36FD18FC7F39 FOREIGN KEY (tarefa_atual_id) REFERENCES ad_tarefa (id)');
            $this->addSql('ALTER TABLE ad_vinc_workflow ADD CONSTRAINT FK_F8DB36FD2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES ad_workflow (id)');
            $this->addSql('ALTER TABLE ad_vinc_workflow ADD CONSTRAINT FK_F8DB36FDF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_workflow ADD CONSTRAINT FK_F8DB36FDAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_workflow ADD CONSTRAINT FK_F8DB36FDA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('DROP TABLE AD_GARANTIA');
            $this->addSql('DROP TABLE AD_MOD_GARANTIA');
            $this->addSql('ALTER TABLE AD_COMPONENTE_DIGITAL ADD (status_verificacao_virus NUMBER(10) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_DOCUMENTO ADD (mod_copia_id NUMBER(10) DEFAULT NULL NULL, dependencia_software VARCHAR2(255) DEFAULT NULL NULL, dependencia_hardware VARCHAR2(255) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_DOCUMENTO ADD CONSTRAINT FK_3088E41EDDE3EC37 FOREIGN KEY (mod_copia_id) REFERENCES ad_mod_copia (id)');
            $this->addSql('CREATE INDEX IDX_3088E41EDDE3EC37 ON AD_DOCUMENTO (mod_copia_id)');
            $this->addSql('ALTER TABLE AD_ESPECIE_DOC_AVULSO ADD (especie_processo_id NUMBER(10) DEFAULT NULL NULL, especie_tarefa_id NUMBER(10) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_ESPECIE_DOC_AVULSO ADD CONSTRAINT FK_D84061E4669869DC FOREIGN KEY (especie_processo_id) REFERENCES ad_especie_processo (id)');
            $this->addSql('ALTER TABLE AD_ESPECIE_DOC_AVULSO ADD CONSTRAINT FK_D84061E4475E1234 FOREIGN KEY (especie_tarefa_id) REFERENCES ad_especie_tarefa (id)');
            $this->addSql('CREATE INDEX IDX_D84061E4669869DC ON AD_ESPECIE_DOC_AVULSO (especie_processo_id)');
            $this->addSql('CREATE INDEX IDX_D84061E4475E1234 ON AD_ESPECIE_DOC_AVULSO (especie_tarefa_id)');
            $this->addSql('ALTER TABLE AD_ESPECIE_PROCESSO DROP CONSTRAINT FK_36AD09DF2C7C2CBA');
            $this->addSql('DROP INDEX idx_36ad09df2c7c2cba');
            $this->addSql('ALTER TABLE AD_ESPECIE_PROCESSO DROP (WORKFLOW_ID)');
            $this->addSql('ALTER TABLE AD_PROCESSO DROP CONSTRAINT FK_FCFDB5FDE9DEA0A1');
            $this->addSql('DROP INDEX uniq_fcfdb5fde9dea0a1');
            $this->addSql('ALTER TABLE AD_PROCESSO DROP (TAREFA_ATUAL_WORKFLOW_ID)');
            $this->addSql('ALTER TABLE AD_TAREFA DROP CONSTRAINT FK_9EF45B4F2C7C2CBA');
            $this->addSql('DROP INDEX idx_9ef45b4f2c7c2cba');
            $this->addSql('ALTER TABLE AD_TAREFA RENAME COLUMN workflow_id TO vinculacao_workflow_id');
            $this->addSql('ALTER TABLE AD_TAREFA ADD CONSTRAINT FK_9EF45B4F28999568 FOREIGN KEY (vinculacao_workflow_id) REFERENCES ad_vinc_workflow (id)');
            $this->addSql('CREATE INDEX IDX_9EF45B4F28999568 ON AD_TAREFA (vinculacao_workflow_id)');
            $this->addSql('ALTER TABLE AD_TRANSICAO_WORKFLOW ADD (qtd_dias_prazo NUMBER(10) DEFAULT NULL NULL)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_D7F90FE32C7C2CBAD960EE384 ON AD_TRANSICAO_WORKFLOW (workflow_id, especie_tarefa_from_id, especie_atividade_id, apagado_em)');
        } else {
            $this->addSql('ALTER TABLE ad_garantia DROP FOREIGN KEY FK_9CE60FE173533CF6');
            $this->addSql('CREATE TABLE ad_avaliacao (id INT AUTO_INCREMENT NOT NULL, objeto_avaliado_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, avaliacao INT NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_EF228DFBD17F50A6 (uuid), INDEX IDX_EF228DFB14B23D30 (objeto_avaliado_id), INDEX IDX_EF228DFBF69C7D9B (criado_por), INDEX IDX_EF228DFBAF2B1A92 (atualizado_por), INDEX IDX_EF228DFBA395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_bookmark (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, componente_digital_id INT NOT NULL, processo_id INT NOT NULL, juntada_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) DEFAULT NULL, pagina INT NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_307A9FCDD17F50A6 (uuid), INDEX IDX_307A9FCDF7BDBDD2 (juntada_id), INDEX IDX_307A9FCDF69C7D9B (criado_por), INDEX IDX_307A9FCDAF2B1A92 (atualizado_por), INDEX IDX_307A9FCDA395BB94 (apagado_por), INDEX usuario_id (usuario_id), INDEX processo_id (processo_id), INDEX componente_digital_id (componente_digital_id), UNIQUE INDEX UNIQ_307A9FCDDB38439EAAA822D2141B5D3A3E8EDA6DFF6A170E (usuario_id, processo_id, componente_digital_id, pagina, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_mod_copia (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', valor VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_215ADBFFD17F50A6 (uuid), UNIQUE INDEX UNIQ_215ADBFF2E892728 (valor), INDEX IDX_215ADBFFF69C7D9B (criado_por), INDEX IDX_215ADBFFAF2B1A92 (atualizado_por), INDEX IDX_215ADBFFA395BB94 (apagado_por), UNIQUE INDEX UNIQ_215ADBFF2E892728FF6A170E (valor, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_objeto_avaliado (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, classe VARCHAR(255) NOT NULL, objeto_id INT NOT NULL, avaliacao_resultante DOUBLE PRECISION DEFAULT NULL, dt_ult_avaliacao DATETIME DEFAULT NULL, qtd_avaliacoes INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_CA4C3CA1D17F50A6 (uuid), INDEX IDX_CA4C3CA1F69C7D9B (criado_por), INDEX IDX_CA4C3CA1AF2B1A92 (atualizado_por), INDEX IDX_CA4C3CA1A395BB94 (apagado_por), INDEX idx_objeto_classe (classe), INDEX idx_objeto_id (objeto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_vinc_esp_proc_workflow (id INT AUTO_INCREMENT NOT NULL, especie_processo_id INT NOT NULL, workflow_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_7801972FD17F50A6 (uuid), INDEX IDX_7801972F669869DC (especie_processo_id), INDEX IDX_7801972F2C7C2CBA (workflow_id), INDEX IDX_7801972FF69C7D9B (criado_por), INDEX IDX_7801972FAF2B1A92 (atualizado_por), INDEX IDX_7801972FA395BB94 (apagado_por), UNIQUE INDEX UNIQ_7801972F2C7C2CBA669869DCFF6A170E (workflow_id, especie_processo_id, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_vinc_trans_workflow (id INT AUTO_INCREMENT NOT NULL, transicao_workflow_id INT NOT NULL, workflow_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_F6E27F5D17F50A6 (uuid), INDEX IDX_F6E27F58DA656D7 (transicao_workflow_id), INDEX IDX_F6E27F52C7C2CBA (workflow_id), INDEX IDX_F6E27F5F69C7D9B (criado_por), INDEX IDX_F6E27F5AF2B1A92 (atualizado_por), INDEX IDX_F6E27F5A395BB94 (apagado_por), UNIQUE INDEX UNIQ_F6E27F52C7C2CBA8DA656D7FF6A170E (workflow_id, transicao_workflow_id, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_vinc_workflow (id INT AUTO_INCREMENT NOT NULL, tarefa_inicial_id INT NOT NULL, tarefa_atual_id INT NOT NULL, workflow_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, concluido TINYINT(1) NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_F8DB36FDD17F50A6 (uuid), INDEX IDX_F8DB36FDB6894EFA (tarefa_inicial_id), INDEX IDX_F8DB36FD18FC7F39 (tarefa_atual_id), INDEX IDX_F8DB36FD2C7C2CBA (workflow_id), INDEX IDX_F8DB36FDF69C7D9B (criado_por), INDEX IDX_F8DB36FDAF2B1A92 (atualizado_por), INDEX IDX_F8DB36FDA395BB94 (apagado_por), UNIQUE INDEX UNIQ_F8DB36FD2C7C2CBAB6894EFAFF6A170E (workflow_id, tarefa_inicial_id, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE ad_avaliacao ADD CONSTRAINT FK_EF228DFB14B23D30 FOREIGN KEY (objeto_avaliado_id) REFERENCES ad_objeto_avaliado (id)');
            $this->addSql('ALTER TABLE ad_avaliacao ADD CONSTRAINT FK_EF228DFBF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_avaliacao ADD CONSTRAINT FK_EF228DFBAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_avaliacao ADD CONSTRAINT FK_EF228DFBA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCDDB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCD141B5D3A FOREIGN KEY (componente_digital_id) REFERENCES ad_componente_digital (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCDAAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCDF7BDBDD2 FOREIGN KEY (juntada_id) REFERENCES ad_juntada (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCDF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCDAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_bookmark ADD CONSTRAINT FK_307A9FCDA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_mod_copia ADD CONSTRAINT FK_215ADBFFF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_mod_copia ADD CONSTRAINT FK_215ADBFFAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_mod_copia ADD CONSTRAINT FK_215ADBFFA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_objeto_avaliado ADD CONSTRAINT FK_CA4C3CA1F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_objeto_avaliado ADD CONSTRAINT FK_CA4C3CA1AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_objeto_avaliado ADD CONSTRAINT FK_CA4C3CA1A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_esp_proc_workflow ADD CONSTRAINT FK_7801972F669869DC FOREIGN KEY (especie_processo_id) REFERENCES ad_especie_processo (id)');
            $this->addSql('ALTER TABLE ad_vinc_esp_proc_workflow ADD CONSTRAINT FK_7801972F2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES ad_workflow (id)');
            $this->addSql('ALTER TABLE ad_vinc_esp_proc_workflow ADD CONSTRAINT FK_7801972FF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_esp_proc_workflow ADD CONSTRAINT FK_7801972FAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_esp_proc_workflow ADD CONSTRAINT FK_7801972FA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_trans_workflow ADD CONSTRAINT FK_F6E27F58DA656D7 FOREIGN KEY (transicao_workflow_id) REFERENCES ad_transicao_workflow (id)');
            $this->addSql('ALTER TABLE ad_vinc_trans_workflow ADD CONSTRAINT FK_F6E27F52C7C2CBA FOREIGN KEY (workflow_id) REFERENCES ad_workflow (id)');
            $this->addSql('ALTER TABLE ad_vinc_trans_workflow ADD CONSTRAINT FK_F6E27F5F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_trans_workflow ADD CONSTRAINT FK_F6E27F5AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_trans_workflow ADD CONSTRAINT FK_F6E27F5A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_workflow ADD CONSTRAINT FK_F8DB36FDB6894EFA FOREIGN KEY (tarefa_inicial_id) REFERENCES ad_tarefa (id)');
            $this->addSql('ALTER TABLE ad_vinc_workflow ADD CONSTRAINT FK_F8DB36FD18FC7F39 FOREIGN KEY (tarefa_atual_id) REFERENCES ad_tarefa (id)');
            $this->addSql('ALTER TABLE ad_vinc_workflow ADD CONSTRAINT FK_F8DB36FD2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES ad_workflow (id)');
            $this->addSql('ALTER TABLE ad_vinc_workflow ADD CONSTRAINT FK_F8DB36FDF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_workflow ADD CONSTRAINT FK_F8DB36FDAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_vinc_workflow ADD CONSTRAINT FK_F8DB36FDA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('DROP TABLE ad_garantia');
            $this->addSql('DROP TABLE ad_mod_garantia');
            $this->addSql('ALTER TABLE ad_componente_digital ADD status_verificacao_virus INT DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_documento ADD mod_copia_id INT DEFAULT NULL, ADD dependencia_software VARCHAR(255) DEFAULT NULL, ADD dependencia_hardware VARCHAR(255) DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_documento ADD CONSTRAINT FK_3088E41EDDE3EC37 FOREIGN KEY (mod_copia_id) REFERENCES ad_mod_copia (id)');
            $this->addSql('CREATE INDEX IDX_3088E41EDDE3EC37 ON ad_documento (mod_copia_id)');
            $this->addSql('ALTER TABLE ad_especie_doc_avulso ADD especie_processo_id INT DEFAULT NULL, ADD especie_tarefa_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_especie_doc_avulso ADD CONSTRAINT FK_D84061E4669869DC FOREIGN KEY (especie_processo_id) REFERENCES ad_especie_processo (id)');
            $this->addSql('ALTER TABLE ad_especie_doc_avulso ADD CONSTRAINT FK_D84061E4475E1234 FOREIGN KEY (especie_tarefa_id) REFERENCES ad_especie_tarefa (id)');
            $this->addSql('CREATE INDEX IDX_D84061E4669869DC ON ad_especie_doc_avulso (especie_processo_id)');
            $this->addSql('CREATE INDEX IDX_D84061E4475E1234 ON ad_especie_doc_avulso (especie_tarefa_id)');
            $this->addSql('ALTER TABLE ad_especie_processo DROP FOREIGN KEY FK_36AD09DF2C7C2CBA');
            $this->addSql('DROP INDEX IDX_36AD09DF2C7C2CBA ON ad_especie_processo');
            $this->addSql('ALTER TABLE ad_especie_processo DROP workflow_id');
            $this->addSql('ALTER TABLE ad_processo DROP FOREIGN KEY FK_FCFDB5FDE9DEA0A1');
            $this->addSql('DROP INDEX UNIQ_FCFDB5FDE9DEA0A1 ON ad_processo');
            $this->addSql('ALTER TABLE ad_processo DROP tarefa_atual_workflow_id');
            $this->addSql('ALTER TABLE ad_tarefa DROP FOREIGN KEY FK_9EF45B4F2C7C2CBA');
            $this->addSql('DROP INDEX IDX_9EF45B4F2C7C2CBA ON ad_tarefa');
            $this->addSql('ALTER TABLE ad_tarefa CHANGE workflow_id vinculacao_workflow_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_tarefa ADD CONSTRAINT FK_9EF45B4F28999568 FOREIGN KEY (vinculacao_workflow_id) REFERENCES ad_vinc_workflow (id)');
            $this->addSql('CREATE INDEX IDX_9EF45B4F28999568 ON ad_tarefa (vinculacao_workflow_id)');
            $this->addSql('ALTER TABLE ad_transicao_workflow ADD qtd_dias_prazo INT DEFAULT NULL');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_D7F90FE32C7C2CBAD960EE3845598AD7FF6A170E ON ad_transicao_workflow (workflow_id, especie_tarefa_from_id, especie_atividade_id, apagado_em)');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE ad_documento DROP CONSTRAINT FK_3088E41EDDE3EC37');
            $this->addSql('ALTER TABLE ad_avaliacao DROP CONSTRAINT FK_EF228DFB14B23D30');
            $this->addSql('ALTER TABLE ad_tarefa DROP CONSTRAINT FK_9EF45B4F28999568');
            $this->addSql('DROP SEQUENCE ad_avaliacao_id_seq');
            $this->addSql('DROP SEQUENCE ad_bookmark_id_seq');
            $this->addSql('DROP SEQUENCE ad_mod_copia_id_seq');
            $this->addSql('DROP SEQUENCE ad_objeto_avaliado_id_seq');
            $this->addSql('DROP SEQUENCE ad_vinc_esp_proc_workflow_id_s');
            $this->addSql('DROP SEQUENCE ad_vinc_trans_workflow_id_seq');
            $this->addSql('DROP SEQUENCE ad_vinc_workflow_id_seq');
            $this->addSql('CREATE SEQUENCE AD_GARANTIA_ID_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE AD_MOD_GARANTIA_ID_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE AD_GARANTIA (ID NUMBER(10) NOT NULL, PROCESSO_ID NUMBER(10) NOT NULL, MOD_GARANTIA_ID NUMBER(10) NOT NULL, CRIADO_POR NUMBER(10) DEFAULT NULL NULL, ATUALIZADO_POR NUMBER(10) DEFAULT NULL NULL, APAGADO_POR NUMBER(10) DEFAULT NULL NULL, DESCRICAO VARCHAR2(255) NOT NULL, VALOR DOUBLE PRECISION NOT NULL, DATA_VALOR DATE NOT NULL, OBSERVACAO VARCHAR2(255) DEFAULT NULL NULL, CRIADO_EM TIMESTAMP(0) DEFAULT NULL NULL, ATUALIZADO_EM TIMESTAMP(0) DEFAULT NULL NULL, APAGADO_EM TIMESTAMP(0) DEFAULT NULL NULL, UUID CHAR(36) NOT NULL, PRIMARY KEY(ID))');
            $this->addSql('CREATE UNIQUE INDEX uniq_9ce60fe1d17f50a6 ON AD_GARANTIA (UUID)');
            $this->addSql('CREATE INDEX idx_9ce60fe1aaa822d2 ON AD_GARANTIA (PROCESSO_ID)');
            $this->addSql('CREATE INDEX idx_9ce60fe1a395bb94 ON AD_GARANTIA (APAGADO_POR)');
            $this->addSql('CREATE INDEX idx_9ce60fe1f69c7d9b ON AD_GARANTIA (CRIADO_POR)');
            $this->addSql('CREATE INDEX idx_9ce60fe1af2b1a92 ON AD_GARANTIA (ATUALIZADO_POR)');
            $this->addSql('CREATE INDEX idx_9ce60fe173533cf6 ON AD_GARANTIA (MOD_GARANTIA_ID)');
            $this->addSql('COMMENT ON COLUMN AD_GARANTIA.UUID IS \'(DC2Type:guid)\'');
            $this->addSql('CREATE TABLE AD_MOD_GARANTIA (ID NUMBER(10) NOT NULL, CRIADO_POR NUMBER(10) DEFAULT NULL NULL, ATUALIZADO_POR NUMBER(10) DEFAULT NULL NULL, APAGADO_POR NUMBER(10) DEFAULT NULL NULL, CRIADO_EM TIMESTAMP(0) DEFAULT NULL NULL, ATUALIZADO_EM TIMESTAMP(0) DEFAULT NULL NULL, APAGADO_EM TIMESTAMP(0) DEFAULT NULL NULL, UUID CHAR(36) NOT NULL, VALOR VARCHAR2(255) NOT NULL, DESCRICAO VARCHAR2(255) NOT NULL, ATIVO NUMBER(1) NOT NULL, PRIMARY KEY(ID))');
            $this->addSql('CREATE UNIQUE INDEX uniq_84778777d17f50a6 ON AD_MOD_GARANTIA (UUID)');
            $this->addSql('CREATE UNIQUE INDEX uniq_847787772e892728 ON AD_MOD_GARANTIA (VALOR)');
            $this->addSql('CREATE INDEX idx_84778777f69c7d9b ON AD_MOD_GARANTIA (CRIADO_POR)');
            $this->addSql('CREATE INDEX idx_84778777af2b1a92 ON AD_MOD_GARANTIA (ATUALIZADO_POR)');
            $this->addSql('CREATE INDEX idx_84778777a395bb94 ON AD_MOD_GARANTIA (APAGADO_POR)');
            $this->addSql('CREATE UNIQUE INDEX uniq_847787772e892728ff6a170e ON AD_MOD_GARANTIA (VALOR, APAGADO_EM)');
            $this->addSql('COMMENT ON COLUMN AD_MOD_GARANTIA.UUID IS \'(DC2Type:guid)\'');
            $this->addSql('ALTER TABLE AD_GARANTIA ADD CONSTRAINT FK_9CE60FE173533CF6 FOREIGN KEY (MOD_GARANTIA_ID) REFERENCES AD_MOD_GARANTIA (ID)');
            $this->addSql('ALTER TABLE AD_GARANTIA ADD CONSTRAINT FK_9CE60FE1A395BB94 FOREIGN KEY (APAGADO_POR) REFERENCES AD_USUARIO (ID)');
            $this->addSql('ALTER TABLE AD_GARANTIA ADD CONSTRAINT FK_9CE60FE1AAA822D2 FOREIGN KEY (PROCESSO_ID) REFERENCES AD_PROCESSO (ID)');
            $this->addSql('ALTER TABLE AD_GARANTIA ADD CONSTRAINT FK_9CE60FE1AF2B1A92 FOREIGN KEY (ATUALIZADO_POR) REFERENCES AD_USUARIO (ID)');
            $this->addSql('ALTER TABLE AD_GARANTIA ADD CONSTRAINT FK_9CE60FE1F69C7D9B FOREIGN KEY (CRIADO_POR) REFERENCES AD_USUARIO (ID)');
            $this->addSql('ALTER TABLE AD_MOD_GARANTIA ADD CONSTRAINT FK_84778777A395BB94 FOREIGN KEY (APAGADO_POR) REFERENCES AD_USUARIO (ID)');
            $this->addSql('ALTER TABLE AD_MOD_GARANTIA ADD CONSTRAINT FK_84778777AF2B1A92 FOREIGN KEY (ATUALIZADO_POR) REFERENCES AD_USUARIO (ID)');
            $this->addSql('ALTER TABLE AD_MOD_GARANTIA ADD CONSTRAINT FK_84778777F69C7D9B FOREIGN KEY (CRIADO_POR) REFERENCES AD_USUARIO (ID)');
            $this->addSql('DROP TABLE ad_avaliacao');
            $this->addSql('DROP TABLE ad_bookmark');
            $this->addSql('DROP TABLE ad_mod_copia');
            $this->addSql('DROP TABLE ad_objeto_avaliado');
            $this->addSql('DROP TABLE ad_vinc_esp_proc_workflow');
            $this->addSql('DROP TABLE ad_vinc_trans_workflow');
            $this->addSql('DROP TABLE ad_vinc_workflow');
            $this->addSql('ALTER TABLE ad_componente_digital DROP (status_verificacao_virus)');
            $this->addSql('ALTER TABLE ad_especie_processo ADD (WORKFLOW_ID NUMBER(10) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE ad_especie_processo ADD CONSTRAINT FK_36AD09DF2C7C2CBA FOREIGN KEY (WORKFLOW_ID) REFERENCES AD_WORKFLOW (ID)');
            $this->addSql('CREATE INDEX idx_36ad09df2c7c2cba ON ad_especie_processo (WORKFLOW_ID)');
            $this->addSql('ALTER TABLE ad_especie_doc_avulso DROP CONSTRAINT FK_D84061E4669869DC');
            $this->addSql('ALTER TABLE ad_especie_doc_avulso DROP CONSTRAINT FK_D84061E4475E1234');
            $this->addSql('DROP INDEX IDX_D84061E4669869DC');
            $this->addSql('DROP INDEX IDX_D84061E4475E1234');
            $this->addSql('ALTER TABLE ad_especie_doc_avulso DROP (especie_processo_id, especie_tarefa_id)');
            $this->addSql('DROP INDEX UNIQ_D7F90FE32C7C2CBAD960EE384');
            $this->addSql('ALTER TABLE ad_transicao_workflow DROP (qtd_dias_prazo)');
            $this->addSql('ALTER TABLE ad_processo ADD (TAREFA_ATUAL_WORKFLOW_ID NUMBER(10) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE ad_processo ADD CONSTRAINT FK_FCFDB5FDE9DEA0A1 FOREIGN KEY (TAREFA_ATUAL_WORKFLOW_ID) REFERENCES AD_TAREFA (ID)');
            $this->addSql('CREATE UNIQUE INDEX uniq_fcfdb5fde9dea0a1 ON ad_processo (TAREFA_ATUAL_WORKFLOW_ID)');
            $this->addSql('DROP INDEX IDX_3088E41EDDE3EC37');
            $this->addSql('ALTER TABLE ad_documento DROP (mod_copia_id, dependencia_software, dependencia_hardware)');
            $this->addSql('DROP INDEX IDX_9EF45B4F28999568');
            $this->addSql('ALTER TABLE ad_tarefa RENAME COLUMN vinculacao_workflow_id TO WORKFLOW_ID');
            $this->addSql('ALTER TABLE ad_tarefa ADD CONSTRAINT FK_9EF45B4F2C7C2CBA FOREIGN KEY (WORKFLOW_ID) REFERENCES AD_WORKFLOW (ID)');
            $this->addSql('CREATE INDEX idx_9ef45b4f2c7c2cba ON ad_tarefa (WORKFLOW_ID)');
        } else {
            $this->addSql('ALTER TABLE ad_documento DROP FOREIGN KEY FK_3088E41EDDE3EC37');
            $this->addSql('ALTER TABLE ad_avaliacao DROP FOREIGN KEY FK_EF228DFB14B23D30');
            $this->addSql('ALTER TABLE ad_tarefa DROP FOREIGN KEY FK_9EF45B4F28999568');
            $this->addSql('CREATE TABLE ad_garantia (id INT AUTO_INCREMENT NOT NULL, processo_id INT NOT NULL, mod_garantia_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, descricao VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, valor DOUBLE PRECISION NOT NULL, data_valor DATE NOT NULL, observacao VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:guid)\', INDEX IDX_9CE60FE1F69C7D9B (criado_por), UNIQUE INDEX UNIQ_9CE60FE1D17F50A6 (uuid), INDEX IDX_9CE60FE173533CF6 (mod_garantia_id), INDEX IDX_9CE60FE1A395BB94 (apagado_por), INDEX IDX_9CE60FE1AAA822D2 (processo_id), INDEX IDX_9CE60FE1AF2B1A92 (atualizado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
            $this->addSql('CREATE TABLE ad_mod_garantia (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:guid)\', valor VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, descricao VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_847787772E892728 (valor), UNIQUE INDEX UNIQ_847787772E892728FF6A170E (valor, apagado_em), UNIQUE INDEX UNIQ_84778777D17F50A6 (uuid), INDEX IDX_84778777A395BB94 (apagado_por), INDEX IDX_84778777AF2B1A92 (atualizado_por), INDEX IDX_84778777F69C7D9B (criado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
            $this->addSql('ALTER TABLE ad_garantia ADD CONSTRAINT FK_9CE60FE173533CF6 FOREIGN KEY (mod_garantia_id) REFERENCES ad_mod_garantia (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_garantia ADD CONSTRAINT FK_9CE60FE1AAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_garantia ADD CONSTRAINT FK_9CE60FE1AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_garantia ADD CONSTRAINT FK_9CE60FE1F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_garantia ADD CONSTRAINT FK_9CE60FE1A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_mod_garantia ADD CONSTRAINT FK_84778777AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_mod_garantia ADD CONSTRAINT FK_84778777F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('ALTER TABLE ad_mod_garantia ADD CONSTRAINT FK_84778777A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('DROP TABLE ad_avaliacao');
            $this->addSql('DROP TABLE ad_bookmark');
            $this->addSql('DROP TABLE ad_mod_copia');
            $this->addSql('DROP TABLE ad_objeto_avaliado');
            $this->addSql('DROP TABLE ad_vinc_esp_proc_workflow');
            $this->addSql('DROP TABLE ad_vinc_trans_workflow');
            $this->addSql('DROP TABLE ad_vinc_workflow');
            $this->addSql('ALTER TABLE ad_componente_digital DROP status_verificacao_virus');
            $this->addSql('DROP INDEX IDX_3088E41EDDE3EC37 ON ad_documento');
            $this->addSql('ALTER TABLE ad_documento DROP mod_copia_id, DROP dependencia_software, DROP dependencia_hardware');
            $this->addSql('ALTER TABLE ad_especie_doc_avulso DROP FOREIGN KEY FK_D84061E4669869DC');
            $this->addSql('ALTER TABLE ad_especie_doc_avulso DROP FOREIGN KEY FK_D84061E4475E1234');
            $this->addSql('DROP INDEX IDX_D84061E4669869DC ON ad_especie_doc_avulso');
            $this->addSql('DROP INDEX IDX_D84061E4475E1234 ON ad_especie_doc_avulso');
            $this->addSql('ALTER TABLE ad_especie_doc_avulso DROP especie_processo_id, DROP especie_tarefa_id');
            $this->addSql('ALTER TABLE ad_especie_processo ADD workflow_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_especie_processo ADD CONSTRAINT FK_36AD09DF2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES ad_workflow (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('CREATE INDEX IDX_36AD09DF2C7C2CBA ON ad_especie_processo (workflow_id)');
            $this->addSql('ALTER TABLE ad_processo ADD tarefa_atual_workflow_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_processo ADD CONSTRAINT FK_FCFDB5FDE9DEA0A1 FOREIGN KEY (tarefa_atual_workflow_id) REFERENCES ad_tarefa (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_FCFDB5FDE9DEA0A1 ON ad_processo (tarefa_atual_workflow_id)');
            $this->addSql('DROP INDEX IDX_9EF45B4F28999568 ON ad_tarefa');
            $this->addSql('ALTER TABLE ad_tarefa CHANGE vinculacao_workflow_id workflow_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_tarefa ADD CONSTRAINT FK_9EF45B4F2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES ad_workflow (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
            $this->addSql('CREATE INDEX IDX_9EF45B4F2C7C2CBA ON ad_tarefa (workflow_id)');
            $this->addSql('DROP INDEX UNIQ_D7F90FE32C7C2CBAD960EE3845598AD7FF6A170E ON ad_transicao_workflow');
            $this->addSql('ALTER TABLE ad_transicao_workflow DROP qtd_dias_prazo');
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
