<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517113313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.3.2 do 1.4.0';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('CREATE SEQUENCE ad_tipo_notificacao_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_aviso_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_vinc_aviso_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql(
                'CREATE TABLE ad_tipo_notificacao (id NUMBER(10) NOT NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))'
            );
            $this->addSql('CREATE UNIQUE INDEX UNIQ_85C2F52AD17F50A6 ON ad_tipo_notificacao (uuid)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_85C2F52A54BD530C ON ad_tipo_notificacao (nome)');
            $this->addSql(
                'CREATE TABLE ad_aviso (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, sistema NUMBER(1) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, ativo NUMBER(1) NOT NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))'
            );
            $this->addSql('CREATE UNIQUE INDEX UNIQ_8F5DAA76D17F50A6 ON ad_aviso (uuid)');
            $this->addSql('CREATE INDEX IDX_8F5DAA76F69C7D9B ON ad_aviso (criado_por)');
            $this->addSql('CREATE INDEX IDX_8F5DAA76AF2B1A92 ON ad_aviso (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_8F5DAA76A395BB94 ON ad_aviso (apagado_por)');
            $this->addSql(
                'CREATE TABLE ad_vinc_aviso (id NUMBER(10) NOT NULL, aviso_id NUMBER(10) NOT NULL, especie_setor_id NUMBER(10) DEFAULT NULL NULL, setor_id NUMBER(10) DEFAULT NULL NULL, usuario_id NUMBER(10) DEFAULT NULL NULL, orgao_central_id NUMBER(10) DEFAULT NULL NULL, unidade_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))'
            );
            $this->addSql('CREATE UNIQUE INDEX UNIQ_E16CA0D1D17F50A6 ON ad_vinc_aviso (uuid)');
            $this->addSql('CREATE INDEX IDX_E16CA0D19D17A5A7 ON ad_vinc_aviso (aviso_id)');
            $this->addSql('CREATE INDEX IDX_E16CA0D187F70880 ON ad_vinc_aviso (especie_setor_id)');
            $this->addSql('CREATE INDEX IDX_E16CA0D14D94F126 ON ad_vinc_aviso (setor_id)');
            $this->addSql('CREATE INDEX IDX_E16CA0D1DB38439E ON ad_vinc_aviso (usuario_id)');
            $this->addSql('CREATE INDEX IDX_E16CA0D18515ADFB ON ad_vinc_aviso (orgao_central_id)');
            $this->addSql('CREATE INDEX IDX_E16CA0D1EDF4B99B ON ad_vinc_aviso (unidade_id)');
            $this->addSql('CREATE INDEX IDX_E16CA0D1F69C7D9B ON ad_vinc_aviso (criado_por)');
            $this->addSql('CREATE INDEX IDX_E16CA0D1AF2B1A92 ON ad_vinc_aviso (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_E16CA0D1A395BB94 ON ad_vinc_aviso (apagado_por)');
            $this->addSql(
                'CREATE UNIQUE INDEX UNIQ_E16CA0D19D17A5A78515ADFB8 ON ad_vinc_aviso (aviso_id, orgao_central_id, especie_setor_id, apagado_em, usuario_id, setor_id)'
            );
            $this->addSql(
                'ALTER TABLE ad_aviso ADD CONSTRAINT FK_8F5DAA76F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_aviso ADD CONSTRAINT FK_8F5DAA76AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_aviso ADD CONSTRAINT FK_8F5DAA76A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D19D17A5A7 FOREIGN KEY (aviso_id) REFERENCES ad_aviso (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D187F70880 FOREIGN KEY (especie_setor_id) REFERENCES ad_especie_setor (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D14D94F126 FOREIGN KEY (setor_id) REFERENCES ad_setor (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D1DB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D18515ADFB FOREIGN KEY (orgao_central_id) REFERENCES ad_mod_orgao_central (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D1EDF4B99B FOREIGN KEY (unidade_id) REFERENCES ad_setor (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D1F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D1AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D1A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)'
            );
            $this->addSql('CREATE UNIQUE INDEX UNIQ_22392F702E892728 ON AD_MOD_ALVO_INIBIDOR (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_FA0D04C92E892728 ON AD_MOD_RELAL_PESSOAL (valor)');
            $this->addSql(
                'ALTER TABLE AD_ATIVIDADE ADD (info_complementar_1 VARCHAR2(255) DEFAULT NULL NULL, info_complementar_2 VARCHAR2(255) DEFAULT NULL NULL, info_complementar_3 VARCHAR2(255) DEFAULT NULL NULL, info_complementar_4 VARCHAR2(255) DEFAULT NULL NULL)'
            );
            $this->addSql(
                'ALTER TABLE AD_COMPONENTE_DIGITAL ADD (crypto_service VARCHAR2(255) DEFAULT NULL NULL, filesystem_service VARCHAR2(255) DEFAULT NULL NULL)'
            );
            $this->addSql('CREATE UNIQUE INDEX UNIQ_4D6253F72E892728 ON AD_MOD_DESTINACAO (valor)');
            $this->addSql(
                'ALTER TABLE AD_NOTIFICACAO ADD (tipo_notificacao_id NUMBER(10) DEFAULT NULL NULL, contexto VARCHAR2(255) DEFAULT NULL NULL)'
            );
            $this->addSql(
                'ALTER TABLE AD_NOTIFICACAO ADD CONSTRAINT FK_71864047B0930A2C FOREIGN KEY (tipo_notificacao_id) REFERENCES ad_tipo_notificacao (id)'
            );
            $this->addSql('CREATE INDEX IDX_71864047B0930A2C ON AD_NOTIFICACAO (tipo_notificacao_id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_DC94719D2E892728 ON AD_MOD_VINC_DOCUMENTO (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_3904CCFB2E892728 ON AD_TIPO_ACAO_WORKFLOW (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_FE9EF8A32E892728 ON AD_MOD_FASE (valor)');
            $this->addSql('DROP INDEX uniq_5551bea08515adfbdb38439ef');
            $this->addSql('DROP INDEX uniq_5551bea04d94f126db38439ef');
            $this->addSql('DROP INDEX uniq_5551bea0edf4b99bdb38439ef');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_611F40562E892728 ON AD_MOD_CATEGORIA_SIGILO (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_932D717D2E892728 ON AD_MOD_GENERO_PESSOA (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_65E99AC52E892728 ON AD_MOD_TEMPLATE (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_52A452802E892728 ON AD_MOD_ACAO_ETIQUETA (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_7BE900832E892728 ON AD_MOD_MODELO (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_9FD5237C2E892728 ON AD_MOD_ETIQUETA (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_366725012E892728 ON AD_MOD_TRANSICAO (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_679C65082E892728 ON AD_MOD_FOLDER (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_2C65A0652E892728 ON AD_MOD_ORGAO_CENTRAL (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_FC4A358C2E892728 ON AD_MOD_NOTIFICACAO (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_561067652E892728 ON AD_MOD_QUAL_PESSOA (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_8A3C3332E892728 ON AD_MOD_DOC_IDENTIFICADOR (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_8BA693A62E892728 ON AD_MOD_AFASTAMENTO (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_6D4C0E652E892728 ON AD_MOD_INTERESSADO (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_C8B89D452E892728 ON AD_MOD_REPRESENTANTE (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_847787772E892728 ON AD_MOD_GARANTIA (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_FB0787EA2E892728 ON AD_MOD_TIPO_INIBIDOR (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_6C367452E892728 ON AD_MOD_REPOSITORIO (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_747FADB92E892728 ON AD_MOD_COLABORADOR (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_839A103C2E892728 ON AD_MOD_VINC_PROCESSO (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_7E6EC4BB2E892728 ON AD_MOD_MEIO (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_10C813622E892728 ON AD_TIPO_VALID_WORKFLOW (valor);');
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql(
                'CREATE TABLE ad_aviso (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, sistema TINYINT(1) NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, ativo TINYINT(1) NOT NULL, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_8F5DAA76D17F50A6 (uuid), INDEX IDX_8F5DAA76F69C7D9B (criado_por), INDEX IDX_8F5DAA76AF2B1A92 (atualizado_por), INDEX IDX_8F5DAA76A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB'
            );
            $this->addSql(
                'CREATE TABLE ad_tipo_notificacao (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_85C2F52AD17F50A6 (uuid), UNIQUE INDEX UNIQ_85C2F52A54BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB'
            );
            $this->addSql(
                'CREATE TABLE ad_vinc_aviso (id INT AUTO_INCREMENT NOT NULL, aviso_id INT NOT NULL, especie_setor_id INT DEFAULT NULL, setor_id INT DEFAULT NULL, usuario_id INT DEFAULT NULL, orgao_central_id INT DEFAULT NULL, unidade_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_E16CA0D1D17F50A6 (uuid), INDEX IDX_E16CA0D19D17A5A7 (aviso_id), INDEX IDX_E16CA0D187F70880 (especie_setor_id), INDEX IDX_E16CA0D14D94F126 (setor_id), INDEX IDX_E16CA0D1DB38439E (usuario_id), INDEX IDX_E16CA0D18515ADFB (orgao_central_id), INDEX IDX_E16CA0D1EDF4B99B (unidade_id), INDEX IDX_E16CA0D1F69C7D9B (criado_por), INDEX IDX_E16CA0D1AF2B1A92 (atualizado_por), INDEX IDX_E16CA0D1A395BB94 (apagado_por), UNIQUE INDEX UNIQ_E16CA0D19D17A5A78515ADFB87F70880FF6A170EDB38439E4D94F126 (aviso_id, orgao_central_id, especie_setor_id, apagado_em, usuario_id, setor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB'
            );
            $this->addSql(
                'ALTER TABLE ad_aviso ADD CONSTRAINT FK_8F5DAA76F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_aviso ADD CONSTRAINT FK_8F5DAA76AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_aviso ADD CONSTRAINT FK_8F5DAA76A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D19D17A5A7 FOREIGN KEY (aviso_id) REFERENCES ad_aviso (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D187F70880 FOREIGN KEY (especie_setor_id) REFERENCES ad_especie_setor (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D14D94F126 FOREIGN KEY (setor_id) REFERENCES ad_setor (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D1DB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D18515ADFB FOREIGN KEY (orgao_central_id) REFERENCES ad_mod_orgao_central (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D1EDF4B99B FOREIGN KEY (unidade_id) REFERENCES ad_setor (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D1F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D1AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_vinc_aviso ADD CONSTRAINT FK_E16CA0D1A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)'
            );
            $this->addSql(
                'ALTER TABLE ad_atividade ADD info_complementar_1 VARCHAR(255) DEFAULT NULL, ADD info_complementar_2 VARCHAR(255) DEFAULT NULL, ADD info_complementar_3 VARCHAR(255) DEFAULT NULL, ADD info_complementar_4 VARCHAR(255) DEFAULT NULL'
            );
            $this->addSql(
                'ALTER TABLE ad_componente_digital ADD crypto_service VARCHAR(255) DEFAULT NULL, ADD filesystem_service VARCHAR(255) DEFAULT NULL'
            );
            $this->addSql('DROP INDEX UNIQ_5551BEA0EDF4B99BDB38439EFF6A170E ON ad_coordenador');
            $this->addSql('DROP INDEX UNIQ_5551BEA04D94F126DB38439EFF6A170E ON ad_coordenador');
            $this->addSql('DROP INDEX UNIQ_5551BEA08515ADFBDB38439EFF6A170E ON ad_coordenador');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_52A452802E892728 ON ad_mod_acao_etiqueta (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_8BA693A62E892728 ON ad_mod_afastamento (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_22392F702E892728 ON ad_mod_alvo_inibidor (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_611F40562E892728 ON ad_mod_categoria_sigilo (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_747FADB92E892728 ON ad_mod_colaborador (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_4D6253F72E892728 ON ad_mod_destinacao (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_8A3C3332E892728 ON ad_mod_doc_identificador (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_9FD5237C2E892728 ON ad_mod_etiqueta (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_FE9EF8A32E892728 ON ad_mod_fase (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_679C65082E892728 ON ad_mod_folder (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_847787772E892728 ON ad_mod_garantia (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_932D717D2E892728 ON ad_mod_genero_pessoa (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_6D4C0E652E892728 ON ad_mod_interessado (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_7E6EC4BB2E892728 ON ad_mod_meio (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_7BE900832E892728 ON ad_mod_modelo (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_FC4A358C2E892728 ON ad_mod_notificacao (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_2C65A0652E892728 ON ad_mod_orgao_central (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_561067652E892728 ON ad_mod_qual_pessoa (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_FA0D04C92E892728 ON ad_mod_relal_pessoal (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_6C367452E892728 ON ad_mod_repositorio (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_C8B89D452E892728 ON ad_mod_representante (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_65E99AC52E892728 ON ad_mod_template (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_FB0787EA2E892728 ON ad_mod_tipo_inibidor (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_366725012E892728 ON ad_mod_transicao (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_DC94719D2E892728 ON ad_mod_vinc_documento (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_839A103C2E892728 ON ad_mod_vinc_processo (valor)');
            $this->addSql(
                'ALTER TABLE ad_notificacao ADD tipo_notificacao_id INT DEFAULT NULL, ADD contexto VARCHAR(255) DEFAULT NULL'
            );
            $this->addSql(
                'ALTER TABLE ad_notificacao ADD CONSTRAINT FK_71864047B0930A2C FOREIGN KEY (tipo_notificacao_id) REFERENCES ad_tipo_notificacao (id)'
            );
            $this->addSql('CREATE INDEX IDX_71864047B0930A2C ON ad_notificacao (tipo_notificacao_id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_3904CCFB2E892728 ON ad_tipo_acao_workflow (valor)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_10C813622E892728 ON ad_tipo_valid_workflow (valor)');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('DROP INDEX uniq_22392f702e892728');
            $this->addSql('DROP INDEX uniq_fa0d04c92e892728');
            $this->addSql('ALTER TABLE AD_ATIVIDADE DROP (INFO_COMPLEMENTAR_1, INFO_COMPLEMENTAR_2, INFO_COMPLEMENTAR_3, INFO_COMPLEMENTAR_4)');
            $this->addSql('ALTER TABLE AD_COMPONENTE_DIGITAL DROP (CRYPTO_SERVICE, FILESYSTEM_SERVICE)');
            $this->addSql('DROP INDEX uniq_4d6253f72e892728');
            $this->addSql('DROP INDEX idx_71864047b0930a2c');
            $this->addSql('ALTER TABLE AD_NOTIFICACAO DROP (TIPO_NOTIFICACAO_ID, CONTEXTO)');
            $this->addSql('DROP INDEX uniq_dc94719d2e892728');
            $this->addSql('DROP INDEX uniq_3904ccfb2e892728');
            $this->addSql('DROP INDEX uniq_fe9ef8a32e892728');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_5551BEA0EDF4B99BDB38439EF ON AD_COORDENADOR (unidade_id, usuario_id, apagado_em)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_5551BEA04D94F126DB38439EF ON AD_COORDENADOR (setor_id, usuario_id, apagado_em)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_5551BEA08515ADFBDB38439EF ON AD_COORDENADOR (orgao_central_id, usuario_id, apagado_em)');
            $this->addSql('DROP INDEX uniq_611f40562e892728');
            $this->addSql('DROP INDEX uniq_932d717d2e892728');
            $this->addSql('DROP INDEX uniq_65e99ac52e892728');
            $this->addSql('DROP INDEX uniq_52a452802e892728');
            $this->addSql('DROP INDEX uniq_7be900832e892728');
            $this->addSql('DROP INDEX uniq_9fd5237c2e892728');
            $this->addSql('DROP INDEX uniq_366725012e892728');
            $this->addSql('DROP INDEX uniq_679c65082e892728');
            $this->addSql('DROP INDEX uniq_2c65a0652e892728');
            $this->addSql('DROP INDEX uniq_fc4a358c2e892728');
            $this->addSql('DROP INDEX uniq_561067652e892728');
            $this->addSql('DROP INDEX uniq_8a3c3332e892728');
            $this->addSql('DROP INDEX uniq_8ba693a62e892728');
            $this->addSql('DROP INDEX uniq_6d4c0e652e892728');
            $this->addSql('DROP INDEX uniq_c8b89d452e892728');
            $this->addSql('DROP INDEX uniq_847787772e892728');
            $this->addSql('DROP INDEX uniq_fb0787ea2e892728');
            $this->addSql('DROP INDEX uniq_6c367452e892728');
            $this->addSql('DROP INDEX uniq_747fadb92e892728');
            $this->addSql('DROP INDEX uniq_839a103c2e892728');
            $this->addSql('DROP INDEX uniq_7e6ec4bb2e892728');
            $this->addSql('DROP INDEX uniq_10c813622e892728');
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_vinc_aviso DROP FOREIGN KEY FK_E16CA0D19D17A5A7');
            $this->addSql('ALTER TABLE ad_notificacao DROP FOREIGN KEY FK_71864047B0930A2C');
            $this->addSql('DROP TABLE ad_aviso');
            $this->addSql('DROP TABLE ad_tipo_notificacao');
            $this->addSql('DROP TABLE ad_vinc_aviso');
            $this->addSql(
                'ALTER TABLE ad_atividade DROP info_complementar_1, DROP info_complementar_2, DROP info_complementar_3, DROP info_complementar_4'
            );
            $this->addSql('ALTER TABLE ad_componente_digital DROP crypto_service, DROP filesystem_service');
            $this->addSql(
                'CREATE UNIQUE INDEX UNIQ_5551BEA0EDF4B99BDB38439EFF6A170E ON ad_coordenador (unidade_id, usuario_id, apagado_em)'
            );
            $this->addSql(
                'CREATE UNIQUE INDEX UNIQ_5551BEA04D94F126DB38439EFF6A170E ON ad_coordenador (setor_id, usuario_id, apagado_em)'
            );
            $this->addSql(
                'CREATE UNIQUE INDEX UNIQ_5551BEA08515ADFBDB38439EFF6A170E ON ad_coordenador (orgao_central_id, usuario_id, apagado_em)'
            );
            $this->addSql('DROP INDEX UNIQ_52A452802E892728 ON ad_mod_acao_etiqueta');
            $this->addSql('DROP INDEX UNIQ_8BA693A62E892728 ON ad_mod_afastamento');
            $this->addSql('DROP INDEX UNIQ_22392F702E892728 ON ad_mod_alvo_inibidor');
            $this->addSql('DROP INDEX UNIQ_611F40562E892728 ON ad_mod_categoria_sigilo');
            $this->addSql('DROP INDEX UNIQ_747FADB92E892728 ON ad_mod_colaborador');
            $this->addSql('DROP INDEX UNIQ_4D6253F72E892728 ON ad_mod_destinacao');
            $this->addSql('DROP INDEX UNIQ_8A3C3332E892728 ON ad_mod_doc_identificador');
            $this->addSql('DROP INDEX UNIQ_9FD5237C2E892728 ON ad_mod_etiqueta');
            $this->addSql('DROP INDEX UNIQ_FE9EF8A32E892728 ON ad_mod_fase');
            $this->addSql('DROP INDEX UNIQ_679C65082E892728 ON ad_mod_folder');
            $this->addSql('DROP INDEX UNIQ_847787772E892728 ON ad_mod_garantia');
            $this->addSql('DROP INDEX UNIQ_932D717D2E892728 ON ad_mod_genero_pessoa');
            $this->addSql('DROP INDEX UNIQ_6D4C0E652E892728 ON ad_mod_interessado');
            $this->addSql('DROP INDEX UNIQ_7E6EC4BB2E892728 ON ad_mod_meio');
            $this->addSql('DROP INDEX UNIQ_7BE900832E892728 ON ad_mod_modelo');
            $this->addSql('DROP INDEX UNIQ_FC4A358C2E892728 ON ad_mod_notificacao');
            $this->addSql('DROP INDEX UNIQ_2C65A0652E892728 ON ad_mod_orgao_central');
            $this->addSql('DROP INDEX UNIQ_561067652E892728 ON ad_mod_qual_pessoa');
            $this->addSql('DROP INDEX UNIQ_FA0D04C92E892728 ON ad_mod_relal_pessoal');
            $this->addSql('DROP INDEX UNIQ_6C367452E892728 ON ad_mod_repositorio');
            $this->addSql('DROP INDEX UNIQ_C8B89D452E892728 ON ad_mod_representante');
            $this->addSql('DROP INDEX UNIQ_65E99AC52E892728 ON ad_mod_template');
            $this->addSql('DROP INDEX UNIQ_FB0787EA2E892728 ON ad_mod_tipo_inibidor');
            $this->addSql('DROP INDEX UNIQ_366725012E892728 ON ad_mod_transicao');
            $this->addSql('DROP INDEX UNIQ_DC94719D2E892728 ON ad_mod_vinc_documento');
            $this->addSql('DROP INDEX UNIQ_839A103C2E892728 ON ad_mod_vinc_processo');
            $this->addSql('DROP INDEX IDX_71864047B0930A2C ON ad_notificacao');
            $this->addSql('ALTER TABLE ad_notificacao DROP tipo_notificacao_id, DROP contexto');
            $this->addSql('DROP INDEX UNIQ_3904CCFB2E892728 ON ad_tipo_acao_workflow');
            $this->addSql('DROP INDEX UNIQ_10C813622E892728 ON ad_tipo_valid_workflow');
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
