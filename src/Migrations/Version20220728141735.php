<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728141735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.8.13 to 1.9.0';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('CREATE SEQUENCE ad_cronjob_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE ad_cronjob (id NUMBER(10) NOT NULL, usuario_ultima_execucao NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, periodicidade VARCHAR2(255) NOT NULL, comando VARCHAR2(255) NOT NULL, status_ultima_execucao NUMBER(10) DEFAULT NULL NULL, ultimo_pid NUMBER(10) DEFAULT NULL NULL, percentual_execucao DOUBLE PRECISION DEFAULT NULL NULL, data_hora_ultima_execucao TIMESTAMP(0) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, ativo NUMBER(1) NOT NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_6890950ED17F50A6 ON ad_cronjob (uuid)');
            $this->addSql('CREATE INDEX IDX_6890950E101080C7 ON ad_cronjob (usuario_ultima_execucao)');
            $this->addSql('CREATE INDEX IDX_6890950EF69C7D9B ON ad_cronjob (criado_por)');
            $this->addSql('CREATE INDEX IDX_6890950EAF2B1A92 ON ad_cronjob (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_6890950EA395BB94 ON ad_cronjob (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_6890950E54BD530CFF6A170E ON ad_cronjob (nome, apagado_em)');
            $this->addSql('ALTER TABLE ad_cronjob ADD CONSTRAINT FK_6890950E101080C7 FOREIGN KEY (usuario_ultima_execucao) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_cronjob ADD CONSTRAINT FK_6890950EF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_cronjob ADD CONSTRAINT FK_6890950EAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_cronjob ADD CONSTRAINT FK_6890950EA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE AD_ETIQUETA ADD (tipo_exec_acao_sugestao NUMBER(10) DEFAULT NULL NULL)');
            $this->addSql('ALTER TABLE AD_VINC_ETIQUETA ADD (extension_object_class CLOB DEFAULT NULL NULL, extension_object_uuid CLOB DEFAULT NULL NULL, acoes_execucao_sugestao CLOB DEFAULT NULL NULL)');
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE TABLE ad_cronjob (id INT AUTO_INCREMENT NOT NULL, usuario_ultima_execucao INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, periodicidade VARCHAR(255) NOT NULL, comando VARCHAR(255) NOT NULL, status_ultima_execucao INT DEFAULT NULL, ultimo_pid INT DEFAULT NULL, percentual_execucao DOUBLE PRECISION DEFAULT NULL, data_hora_ultima_execucao DATETIME DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', ativo TINYINT(1) NOT NULL, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6890950ED17F50A6 (uuid), INDEX IDX_6890950E101080C7 (usuario_ultima_execucao), INDEX IDX_6890950EF69C7D9B (criado_por), INDEX IDX_6890950EAF2B1A92 (atualizado_por), INDEX IDX_6890950EA395BB94 (apagado_por), UNIQUE INDEX UNIQ_6890950E54BD530CFF6A170E (nome, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE ad_cronjob ADD CONSTRAINT FK_6890950E101080C7 FOREIGN KEY (usuario_ultima_execucao) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_cronjob ADD CONSTRAINT FK_6890950EF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_cronjob ADD CONSTRAINT FK_6890950EAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_cronjob ADD CONSTRAINT FK_6890950EA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_etiqueta ADD tipo_exec_acao_sugestao INT DEFAULT NULL');
            $this->addSql('ALTER TABLE ad_vinc_etiqueta ADD extension_object_class LONGTEXT DEFAULT NULL, ADD extension_object_uuid LONGTEXT DEFAULT NULL, ADD acoes_execucao_sugestao LONGTEXT DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('DROP SEQUENCE ad_cronjob_id_seq');
            $this->addSql('DROP TABLE ad_cronjob');
            $this->addSql('ALTER TABLE ad_vinc_etiqueta DROP (extension_object_class, extension_object_uuid, acoes_execucao_sugestao)');
            $this->addSql('ALTER TABLE ad_etiqueta DROP (tipo_exec_acao_sugestao)');
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('DROP TABLE ad_cronjob');
            $this->addSql('ALTER TABLE ad_etiqueta DROP tipo_exec_acao_sugestao');
            $this->addSql('ALTER TABLE ad_vinc_etiqueta DROP extension_object_class, DROP extension_object_uuid, DROP acoes_execucao_sugestao');
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
