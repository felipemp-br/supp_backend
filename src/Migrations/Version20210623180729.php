<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623180729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.4.2 do 1.5.0';
    }

    public function up(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE SEQUENCE BR_STATUS_BARRAMENTO_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE br_status_barramento (id NUMBER(10) NOT NULL, processo_id NUMBER(10) DEFAULT NULL NULL, documento_avulso_id NUMBER(10) DEFAULT NULL NULL, tramitacao_id NUMBER(10) DEFAULT NULL NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, idt_componente_digital NUMBER(10) DEFAULT NULL NULL, idt NUMBER(10) NOT NULL, cod_situacao_tramitacao NUMBER(10) NOT NULL, codigo_erro NUMBER(10) DEFAULT NULL NULL, mensagem_erro VARCHAR2(255) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE INDEX IDX_DE6F17E0AAA822D2 ON br_status_barramento (processo_id)');
            $this->addSql('CREATE INDEX IDX_DE6F17E0864A09CC ON br_status_barramento (documento_avulso_id)');
            $this->addSql('CREATE INDEX IDX_DE6F17E02D67BE79 ON br_status_barramento (tramitacao_id)');
            $this->addSql('CREATE INDEX IDX_DE6F17E0F69C7D9B ON br_status_barramento (criado_por)');
            $this->addSql('CREATE INDEX IDX_DE6F17E0AF2B1A92 ON br_status_barramento (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_DE6F17E0A395BB94 ON br_status_barramento (apagado_por)');
            $this->addSql('CREATE SEQUENCE BR_VINC_PESSOA_BARRAMENTO_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1');
            $this->addSql('CREATE TABLE br_vinc_pessoa_barramento (id NUMBER(10) NOT NULL, pessoa_id NUMBER(10) NOT NULL, criado_por NUMBER(10) DEFAULT NULL NULL, atualizado_por NUMBER(10) DEFAULT NULL NULL, apagado_por NUMBER(10) DEFAULT NULL NULL, uuid CHAR(36) NOT NULL, repositorio_id NUMBER(10) NOT NULL, nome_repositorio VARCHAR2(255) DEFAULT NULL NULL, estrutura_id NUMBER(10) NOT NULL, nome_estrutura VARCHAR2(255) DEFAULT NULL NULL, criado_em TIMESTAMP(0) DEFAULT NULL NULL, atualizado_em TIMESTAMP(0) DEFAULT NULL NULL, apagado_em TIMESTAMP(0) DEFAULT NULL NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_4358424DF6FA0A5 ON br_vinc_pessoa_barramento (pessoa_id)');
            $this->addSql('CREATE INDEX IDX_4358424F69C7D9B ON br_vinc_pessoa_barramento (criado_por)');
            $this->addSql('CREATE INDEX IDX_4358424AF2B1A92 ON br_vinc_pessoa_barramento (atualizado_por)');
            $this->addSql('CREATE INDEX IDX_4358424A395BB94 ON br_vinc_pessoa_barramento (apagado_por)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_4358424DF6FA0A5BED76CEBB3 ON br_vinc_pessoa_barramento (pessoa_id, repositorio_id, estrutura_id, apagado_em)');
            $this->addSql('ALTER TABLE br_status_barramento ADD CONSTRAINT FK_DE6F17E0AAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE br_status_barramento ADD CONSTRAINT FK_DE6F17E0864A09CC FOREIGN KEY (documento_avulso_id) REFERENCES ad_doc_avulso (id)');
            $this->addSql('ALTER TABLE br_status_barramento ADD CONSTRAINT FK_DE6F17E02D67BE79 FOREIGN KEY (tramitacao_id) REFERENCES ad_tramitacao (id)');
            $this->addSql('ALTER TABLE br_status_barramento ADD CONSTRAINT FK_DE6F17E0F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE br_status_barramento ADD CONSTRAINT FK_DE6F17E0AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE br_status_barramento ADD CONSTRAINT FK_DE6F17E0A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE br_vinc_pessoa_barramento ADD CONSTRAINT FK_4358424DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE br_vinc_pessoa_barramento ADD CONSTRAINT FK_4358424F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE br_vinc_pessoa_barramento ADD CONSTRAINT FK_4358424AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE br_vinc_pessoa_barramento ADD CONSTRAINT FK_4358424A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
        } else {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE TABLE br_status_barramento (id INT AUTO_INCREMENT NOT NULL, processo_id INT DEFAULT NULL, documento_avulso_id INT DEFAULT NULL, tramitacao_id INT DEFAULT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', idt_componente_digital INT DEFAULT NULL, idt INT NOT NULL, cod_situacao_tramitacao INT NOT NULL, codigo_erro INT DEFAULT NULL, mensagem_erro VARCHAR(255) DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, INDEX IDX_DE6F17E0AAA822D2 (processo_id), INDEX IDX_DE6F17E0864A09CC (documento_avulso_id), INDEX IDX_DE6F17E02D67BE79 (tramitacao_id), INDEX IDX_DE6F17E0F69C7D9B (criado_por), INDEX IDX_DE6F17E0AF2B1A92 (atualizado_por), INDEX IDX_DE6F17E0A395BB94 (apagado_por), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE br_vinc_pessoa_barramento (id INT AUTO_INCREMENT NOT NULL, pessoa_id INT NOT NULL, criado_por INT DEFAULT NULL, atualizado_por INT DEFAULT NULL, apagado_por INT DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', repositorio_id INT NOT NULL, nome_repositorio VARCHAR(255) DEFAULT NULL, estrutura_id INT NOT NULL, nome_estrutura VARCHAR(255) DEFAULT NULL, criado_em DATETIME DEFAULT NULL, atualizado_em DATETIME DEFAULT NULL, apagado_em DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4358424DF6FA0A5 (pessoa_id), INDEX IDX_4358424F69C7D9B (criado_por), INDEX IDX_4358424AF2B1A92 (atualizado_por), INDEX IDX_4358424A395BB94 (apagado_por), UNIQUE INDEX UNIQ_4358424DF6FA0A5BED76CEBB3E3EF61FF6A170E (pessoa_id, repositorio_id, estrutura_id, apagado_em), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE br_status_barramento ADD CONSTRAINT FK_DE6F17E0AAA822D2 FOREIGN KEY (processo_id) REFERENCES ad_processo (id)');
            $this->addSql('ALTER TABLE br_status_barramento ADD CONSTRAINT FK_DE6F17E0864A09CC FOREIGN KEY (documento_avulso_id) REFERENCES ad_doc_avulso (id)');
            $this->addSql('ALTER TABLE br_status_barramento ADD CONSTRAINT FK_DE6F17E02D67BE79 FOREIGN KEY (tramitacao_id) REFERENCES ad_tramitacao (id)');
            $this->addSql('ALTER TABLE br_status_barramento ADD CONSTRAINT FK_DE6F17E0F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE br_status_barramento ADD CONSTRAINT FK_DE6F17E0AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE br_status_barramento ADD CONSTRAINT FK_DE6F17E0A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE br_vinc_pessoa_barramento ADD CONSTRAINT FK_4358424DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES ad_pessoa (id)');
            $this->addSql('ALTER TABLE br_vinc_pessoa_barramento ADD CONSTRAINT FK_4358424F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE br_vinc_pessoa_barramento ADD CONSTRAINT FK_4358424AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
            $this->addSql('ALTER TABLE br_vinc_pessoa_barramento ADD CONSTRAINT FK_4358424A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');
        }
    }

    public function down(Schema $schema): void
    {
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('DROP TABLE br_status_barramento');
            $this->addSql('DROP TABLE br_vinc_pessoa_barramento');
        } else {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('DROP TABLE br_status_barramento');
            $this->addSql('DROP TABLE br_vinc_pessoa_barramento');
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
