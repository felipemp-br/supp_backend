<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223223521ReceitaFederal extends AbstractMigration
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
            $this->addSql('CREATE SEQUENCE rf_seq_receita_federal_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1');

            $this->addSql('CREATE TABLE rf_seq_receita_federal (id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, codigo VARCHAR2(255) NOT NULL, valor CLOB NOT NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_2529959CD17F50A6 ON rf_seq_receita_federal (uuid)');
            $this->addSql('CREATE INDEX IDX_2529959CF69C7D9B ON rf_seq_receita_federal (criado_por)');
            $this->addSql('CREATE INDEX IDX_2529959CAF2B1A92 ON rf_seq_receita_federal (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_2529959CA395BB94 ON rf_seq_receita_federal (apagado_por)');
            $this->addSql('COMMENT ON COLUMN rf_seq_receita_federal.uuid IS \'(DC2Type:guid)\'');

            $this->addSql('ALTER TABLE rf_seq_receita_federal ADD CONSTRAINT FK_2529959CF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE rf_seq_receita_federal ADD CONSTRAINT FK_2529959CAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE rf_seq_receita_federal ADD CONSTRAINT FK_2529959CA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

        } else {
            $this->addSql('CREATE TABLE rf_seq_receita_federal (id INT AUTO_INCREMENT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, codigo VARCHAR(255) NOT NULL, valor LONGTEXT NOT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_2529959CD17F50A6 (uuid), INDEX IDX_2529959CF69C7D9B (criado_por), INDEX IDX_2529959CAF2B1A92 (atualizado_por), INDEX IDX_2529959CA395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

            $this->addSql('ALTER TABLE rf_seq_receita_federal ADD CONSTRAINT FK_2529959CF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE rf_seq_receita_federal ADD CONSTRAINT FK_2529959CAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE rf_seq_receita_federal ADD CONSTRAINT FK_2529959CA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('DROP SEQUENCE rf_seq_receita_federal_id_seq');

            $this->addSql('ALTER TABLE rf_seq_receita_federal DROP CONSTRAINT FK_2529959CF69C7D9B');
            $this->addSql('ALTER TABLE rf_seq_receita_federal DROP CONSTRAINT FK_2529959CAF2B1A92');
            $this->addSql('ALTER TABLE rf_seq_receita_federal DROP CONSTRAINT FK_2529959CA395BB94');

            $this->addSql('DROP TABLE rf_seq_receita_federal');

        } else {
            $this->addSql('ALTER TABLE rf_seq_receita_federal DROP FOREIGN KEY FK_2529959CF69C7D9B');
            $this->addSql('ALTER TABLE rf_seq_receita_federal DROP FOREIGN KEY FK_2529959CAF2B1A92');
            $this->addSql('ALTER TABLE rf_seq_receita_federal DROP FOREIGN KEY FK_2529959CA395BB94');

            $this->addSql('DROP TABLE rf_seq_receita_federal');
        }
    }
}
