<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116182129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação da tabela ad_vinc_doc_ass_ext com ajustes para PostgreSQL.';
    }

    public function up(Schema $schema): void
    {
        // Ajuste para PostgreSQL: Substituir AUTO_INCREMENT por SERIAL
        $this->addSql('CREATE TABLE ad_vinc_doc_ass_ext (
            id SERIAL NOT NULL, 
            documento_id INT NOT NULL, 
            usuario_id INT DEFAULT NULL, 
            criado_por INT DEFAULT NULL, 
            atualizado_por INT DEFAULT NULL, 
            apagado_por INT DEFAULT NULL, 
            numero_documento_principal VARCHAR(255) DEFAULT NULL, 
            email VARCHAR(255) DEFAULT NULL, 
            expira_em TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
            criado_em TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
            atualizado_em TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
            apagado_em TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
            uuid UUID NOT NULL, 
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E4B36BDD17F50A6 ON ad_vinc_doc_ass_ext (uuid)');
        $this->addSql('CREATE INDEX IDX_5E4B36BD45C0CF75 ON ad_vinc_doc_ass_ext (documento_id)');
        $this->addSql('CREATE INDEX IDX_5E4B36BDDB38439E ON ad_vinc_doc_ass_ext (usuario_id)');
        $this->addSql('CREATE INDEX IDX_5E4B36BDF69C7D9B ON ad_vinc_doc_ass_ext (criado_por)');
        $this->addSql('CREATE INDEX IDX_5E4B36BDAF2B1A92 ON ad_vinc_doc_ass_ext (atualizado_por)');
        $this->addSql('CREATE INDEX IDX_5E4B36BDA395BB94 ON ad_vinc_doc_ass_ext (apagado_por)');
        $this->addSql('CREATE INDEX numero_documento_principal ON ad_vinc_doc_ass_ext (numero_documento_principal)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E4B36BD45C0CF75DB38439E80648646FF6A170E ON ad_vinc_doc_ass_ext (documento_id, usuario_id, numero_documento_principal, apagado_em)');
        $this->addSql('ALTER TABLE ad_vinc_doc_ass_ext ADD CONSTRAINT FK_5E4B36BD45C0CF75 FOREIGN KEY (documento_id) REFERENCES ad_documento (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ad_vinc_doc_ass_ext ADD CONSTRAINT FK_5E4B36BDDB38439E FOREIGN KEY (usuario_id) REFERENCES ad_usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ad_vinc_doc_ass_ext ADD CONSTRAINT FK_5E4B36BDF69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ad_vinc_doc_ass_ext ADD CONSTRAINT FK_5E4B36BDAF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ad_vinc_doc_ass_ext ADD CONSTRAINT FK_5E4B36BDA395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ad_vinc_doc_ass_ext DROP CONSTRAINT FK_5E4B36BD45C0CF75');
        $this->addSql('ALTER TABLE ad_vinc_doc_ass_ext DROP CONSTRAINT FK_5E4B36BDDB38439E');
        $this->addSql('ALTER TABLE ad_vinc_doc_ass_ext DROP CONSTRAINT FK_5E4B36BDF69C7D9B');
        $this->addSql('ALTER TABLE ad_vinc_doc_ass_ext DROP CONSTRAINT FK_5E4B36BDAF2B1A92');
        $this->addSql('ALTER TABLE ad_vinc_doc_ass_ext DROP CONSTRAINT FK_5E4B36BDA395BB94');
        $this->addSql('DROP TABLE ad_vinc_doc_ass_ext');
    }
}
