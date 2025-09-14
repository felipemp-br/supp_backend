<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530181738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.8.6 to 1.8.7';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE SEQUENCE ad_config_modulo_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_modulo_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE ad_config_modulo (id NUMBER(10) NOT NULL, modulo_id NUMBER(10) NOT NULL, config_module_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, data_schema CLOB DEFAULT NULL NULL, data_type VARCHAR2(255) NOT NULL, mandatory NUMBER(1) NOT NULL, data_value CLOB DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_57ABEDDED17F50A6 ON ad_config_modulo (uuid)');
            $this->addSql('CREATE INDEX IDX_57ABEDDEC07F55F5 ON ad_config_modulo (modulo_id)');
            $this->addSql('CREATE INDEX IDX_57ABEDDECE1482AC ON ad_config_modulo (config_module_id)');
            $this->addSql('CREATE INDEX IDX_57ABEDDEF69C7D9B ON ad_config_modulo (criado_por)');
            $this->addSql('CREATE INDEX IDX_57ABEDDEAF2B1A92 ON ad_config_modulo (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_57ABEDDEA395BB94 ON ad_config_modulo (apagado_por)');
            $this->addSql('CREATE INDEX IDX_57ABEDDEFF6A170EBF396750 ON ad_config_modulo (apagado_em, id)');
            $this->addSql('CREATE TABLE ad_modulo (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, prefixo VARCHAR2(255) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, sigla VARCHAR2(25) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_711ED8C3D17F50A6 ON ad_modulo (uuid)');
            $this->addSql('CREATE INDEX IDX_711ED8C3F69C7D9B ON ad_modulo (criado_por)');
            $this->addSql('CREATE INDEX IDX_711ED8C3AF2B1A92 ON ad_modulo (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_711ED8C3A395BB94 ON ad_modulo (apagado_por)');
            $this->addSql('CREATE INDEX IDX_711ED8C3FF6A170EBF396750 ON ad_modulo (apagado_em, id)');
            $this->addSql('ALTER TABLE ad_config_modulo ADD CONSTRAINT FK_57ABEDDEC07F55F5 FOREIGN KEY (modulo_id) REFERENCES ad_modulo (id)');
            $this->addSql('ALTER TABLE ad_config_modulo ADD CONSTRAINT FK_57ABEDDECE1482AC FOREIGN KEY (config_module_id) REFERENCES ad_config_modulo (id)');
            $this->addSql('ALTER TABLE ad_config_modulo ADD CONSTRAINT FK_57ABEDDEF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_config_modulo ADD CONSTRAINT FK_57ABEDDEAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_config_modulo ADD CONSTRAINT FK_57ABEDDEA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_modulo ADD CONSTRAINT FK_711ED8C3F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_modulo ADD CONSTRAINT FK_711ED8C3AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_modulo ADD CONSTRAINT FK_711ED8C3A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE AD_CLASSIFICACAO MODIFY (observacao VARCHAR2(500) DEFAULT NULL)');
            $this->addSql('ALTER TABLE AD_USUARIO ADD (password_atualizado_em TIMESTAMP(0) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE ad_vinc_etiqueta ADD (usuario_aprov_sugestao_id NUMBER(10) DEFAULT NULL NULL, sugestao NUMBER(1) DEFAULT NULL NULL, data_hora_aprov_sugestao TIMESTAMP(0) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE ad_vinc_etiqueta MODIFY (uuid CHAR(36) DEFAULT NULL)');
            $this->addSql('ALTER TABLE ad_vinc_etiqueta ADD CONSTRAINT FK_F04208D115D466CD FOREIGN KEY (usuario_aprov_sugestao_id) REFERENCES usuarios (id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_F04208D1D17F50A6 ON ad_vinc_etiqueta (uuid)');
            $this->addSql('CREATE INDEX IDX_F04208D115D466CD ON ad_vinc_etiqueta (usuario_aprov_sugestao_id)');
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE TABLE ad_config_modulo (id INT AUTO_INCREMENT NOT NULL, modulo_id INT NOT NULL, config_module_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, data_schema LONGTEXT DEFAULT NULL, data_type VARCHAR(255) NOT NULL, mandatory TINYINT(1) NOT NULL, data_value LONGTEXT DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_57ABEDDED17F50A6 (uuid), INDEX IDX_57ABEDDEC07F55F5 (modulo_id), INDEX IDX_57ABEDDECE1482AC (config_module_id), INDEX IDX_57ABEDDEF69C7D9B (criado_por), INDEX IDX_57ABEDDEAF2B1A92 (atualizado_por), INDEX IDX_57ABEDDEA395BB94 (apagado_por), INDEX IDX_57ABEDDEFF6A170EBF396750 (apagado_em, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_modulo (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, prefixo VARCHAR(255) NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, sigla VARCHAR(25) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_711ED8C3D17F50A6 (uuid), INDEX IDX_711ED8C3F69C7D9B (criado_por), INDEX IDX_711ED8C3AF2B1A92 (atualizado_por), INDEX IDX_711ED8C3A395BB94 (apagado_por), INDEX IDX_711ED8C3FF6A170EBF396750 (apagado_em, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE ad_config_modulo ADD CONSTRAINT FK_57ABEDDEC07F55F5 FOREIGN KEY (modulo_id) REFERENCES ad_modulo (id)');
            $this->addSql('ALTER TABLE ad_config_modulo ADD CONSTRAINT FK_57ABEDDECE1482AC FOREIGN KEY (config_module_id) REFERENCES ad_config_modulo (id)');
            $this->addSql('ALTER TABLE ad_config_modulo ADD CONSTRAINT FK_57ABEDDEF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_config_modulo ADD CONSTRAINT FK_57ABEDDEAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_config_modulo ADD CONSTRAINT FK_57ABEDDEA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_modulo ADD CONSTRAINT FK_711ED8C3F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_modulo ADD CONSTRAINT FK_711ED8C3AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_modulo ADD CONSTRAINT FK_711ED8C3A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_classificacao CHANGE observacao observacao VARCHAR(500) DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_usuario ADD password_atualizado_em DATETIME DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_vinc_etiqueta ADD usuario_aprov_sugestao_id INT DEFAULT NULL, ADD sugestao TINYINT(1) DEFAULT NULL, ADD data_hora_aprov_sugestao DATETIME DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_vinc_etiqueta ADD CONSTRAINT FK_F04208D14D2F1ADA FOREIGN KEY (usuario_aprov_sugestao_id) REFERENCES ad_usuario (id)');
            $this->addSql('CREATE INDEX IDX_F04208D14D2F1ADA ON ad_vinc_etiqueta (usuario_aprov_sugestao_id)');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_config_modulo DROP CONSTRAINT FK_57ABEDDECE1482AC');
            $this->addSql('ALTER TABLE ad_config_modulo DROP CONSTRAINT FK_57ABEDDEC07F55F5');
            $this->addSql('DROP SEQUENCE ad_config_modulo_id_seq');
            $this->addSql('DROP SEQUENCE ad_modulo_id_seq');
            $this->addSql('DROP TABLE ad_config_modulo');
            $this->addSql('DROP TABLE ad_modulo');
            $this->addSql('ALTER TABLE ad_usuario DROP (password_atualizado_em)');
            $this->addSql('ALTER TABLE ad_classificacao MODIFY (OBSERVACAO VARCHAR2(255) DEFAULT NULL)');
            $this->addSql('ALTER TABLE ad_vinc_etiqueta DROP (usuario_aprov_sugestao_id, sugestao, data_hora_aprov_sugestao)');
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_config_modulo DROP FOREIGN KEY FK_57ABEDDECE1482AC');
            $this->addSql('ALTER TABLE ad_config_modulo DROP FOREIGN KEY FK_57ABEDDEC07F55F5');
            $this->addSql('DROP TABLE ad_config_modulo');
            $this->addSql('DROP TABLE ad_modulo');
            $this->addSql('ALTER TABLE ad_classificacao CHANGE observacao observacao VARCHAR(255) DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_usuario DROP password_atualizado_em');
            $this->addSql('ALTER TABLE ad_vinc_etiqueta DROP FOREIGN KEY FK_F04208D14D2F1ADA');
            $this->addSql('DROP INDEX IDX_F04208D14D2F1ADA ON ad_vinc_etiqueta');
            $this->addSql('ALTER TABLE ad_vinc_etiqueta DROP usuario_aprov_sugestao_id, DROP sugestao, DROP data_hora_aprov_sugestao');
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
