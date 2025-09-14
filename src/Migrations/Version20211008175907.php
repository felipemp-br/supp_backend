<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211008175907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.6.10 do 1.6.11';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE SEQUENCE ad_conta_email_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE SEQUENCE ad_servidor_email_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE ad_conta_email (id NUMBER(10) NOT NULL, setor_id NUMBER(10) NOT NULL, servidor_email_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, metodo_autenticacao VARCHAR2(255) DEFAULT NULL NULL, login VARCHAR2(255) NOT NULL, senha VARCHAR2(255) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_E4047EAFD17F50A6 ON ad_conta_email (uuid)');
            $this->addSql('CREATE INDEX IDX_E4047EAF4D94F126 ON ad_conta_email (setor_id)');
            $this->addSql('CREATE INDEX IDX_E4047EAFF2F78788 ON ad_conta_email (servidor_email_id)');
            $this->addSql('CREATE INDEX IDX_E4047EAFF69C7D9B ON ad_conta_email (criado_por)');
            $this->addSql('CREATE INDEX IDX_E4047EAFAF2B1A92 ON ad_conta_email (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_E4047EAFA395BB94 ON ad_conta_email (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_E4047EAF54BD530C4D94F126F ON ad_conta_email (nome, setor_id, apagado_em)');
            $this->addSql('CREATE TABLE ad_servidor_email (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, host VARCHAR2(255) NOT NULL, porta NUMBER(10) NOT NULL, protocolo VARCHAR2(255) NOT NULL, metodo_encriptacao VARCHAR2(255) DEFAULT NULL NULL, valida_certificado NUMBER(1) NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, nome VARCHAR2(255) NOT NULL, descricao VARCHAR2(255) NOT NULL, ativo NUMBER(1) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_D3D04E1BD17F50A6 ON ad_servidor_email (uuid)');
            $this->addSql('CREATE INDEX IDX_D3D04E1BF69C7D9B ON ad_servidor_email (criado_por)');
            $this->addSql('CREATE INDEX IDX_D3D04E1BAF2B1A92 ON ad_servidor_email (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_D3D04E1BA395BB94 ON ad_servidor_email (apagado_por)');
            $this->addSql('ALTER TABLE ad_conta_email ADD CONSTRAINT FK_E4047EAF4D94F126 FOREIGN KEY (setor_id) REFERENCES ad_setor (id)');
            $this->addSql('ALTER TABLE ad_conta_email ADD CONSTRAINT FK_E4047EAFF2F78788 FOREIGN KEY (servidor_email_id) REFERENCES ad_servidor_email (id)');
            $this->addSql('ALTER TABLE ad_conta_email ADD CONSTRAINT FK_E4047EAFF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_conta_email ADD CONSTRAINT FK_E4047EAFAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_conta_email ADD CONSTRAINT FK_E4047EAFA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_servidor_email ADD CONSTRAINT FK_D3D04E1BF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_servidor_email ADD CONSTRAINT FK_D3D04E1BAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_servidor_email ADD CONSTRAINT FK_D3D04E1BA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE TABLE ad_conta_email (id INT AUTO_INCREMENT NOT NULL, setor_id INT NOT NULL, servidor_email_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, metodo_autenticacao VARCHAR(255) DEFAULT NULL, login VARCHAR(255) NOT NULL, senha VARCHAR(255) NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_E4047EAFD17F50A6 (uuid), INDEX IDX_E4047EAF4D94F126 (setor_id), INDEX IDX_E4047EAFF2F78788 (servidor_email_id), INDEX IDX_E4047EAFF69C7D9B (criado_por), INDEX IDX_E4047EAFAF2B1A92 (atualizado_por), INDEX IDX_E4047EAFA395BB94 (apagado_por), UNIQUE INDEX UNIQ_E4047EAF54BD530C4D94F126FF6A170E (nome, setor_id, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE ad_servidor_email (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, host VARCHAR(255) NOT NULL, porta INT NOT NULL, protocolo VARCHAR(255) NOT NULL, metodo_encriptacao VARCHAR(255) DEFAULT NULL, valida_certificado TINYINT(1) NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D3D04E1BD17F50A6 (uuid), INDEX IDX_D3D04E1BF69C7D9B (criado_por), INDEX IDX_D3D04E1BAF2B1A92 (atualizado_por), INDEX IDX_D3D04E1BA395BB94 (apagado_por), UNIQUE INDEX UNIQ_D3D04E1B54BD530CFF6A170E (nome, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE ad_conta_email ADD CONSTRAINT FK_E4047EAF4D94F126 FOREIGN KEY (setor_id) REFERENCES ad_setor (id)');
            $this->addSql('ALTER TABLE ad_conta_email ADD CONSTRAINT FK_E4047EAFF2F78788 FOREIGN KEY (servidor_email_id) REFERENCES ad_servidor_email (id)');
            $this->addSql('ALTER TABLE ad_conta_email ADD CONSTRAINT FK_E4047EAFF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_conta_email ADD CONSTRAINT FK_E4047EAFAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_conta_email ADD CONSTRAINT FK_E4047EAFA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_servidor_email ADD CONSTRAINT FK_D3D04E1BF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_servidor_email ADD CONSTRAINT FK_D3D04E1BAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE ad_servidor_email ADD CONSTRAINT FK_D3D04E1BA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_conta_email DROP CONSTRAINT FK_E4047EAFF2F78788');
            $this->addSql('DROP SEQUENCE ad_conta_email_id_seq');
            $this->addSql('DROP SEQUENCE ad_servidor_email_id_seq');
            $this->addSql('DROP TABLE ad_conta_email');
            $this->addSql('DROP TABLE ad_servidor_email');
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE ad_conta_email DROP FOREIGN KEY FK_E4047EAFF2F78788');
            $this->addSql('DROP TABLE ad_conta_email');
            $this->addSql('DROP TABLE ad_servidor_email');
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
