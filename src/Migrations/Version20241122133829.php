<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241122133829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adaptação da migration para PostgreSQL';
    }

    public function up(Schema $schema): void
    {
        // Criando a tabela sem índices
        $this->addSql('CREATE TABLE ad_mod_nat_juridica (
            id SERIAL NOT NULL, 
            criado_por INT DEFAULT NULL, 
            atualizado_por INT DEFAULT NULL, 
            apagado_por INT DEFAULT NULL, 
            criado_em TIMESTAMP DEFAULT NULL, 
            atualizado_em TIMESTAMP DEFAULT NULL, 
            apagado_em TIMESTAMP DEFAULT NULL, 
            uuid UUID NOT NULL,
            valor VARCHAR(255) NOT NULL, 
            descricao VARCHAR(255) NOT NULL, 
            ativo BOOLEAN NOT NULL, 
            PRIMARY KEY(id)
        )');

        // Adicionando o comentário na coluna uuid separadamente
        $this->addSql("COMMENT ON COLUMN ad_mod_nat_juridica.uuid IS '(DC2Type:guid)'");

        // Criando os índices separadamente
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3342F624D17F50A6 ON ad_mod_nat_juridica (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3342F6242E892728 ON ad_mod_nat_juridica (valor)');
        $this->addSql('CREATE INDEX IDX_3342F624F69C7D9B ON ad_mod_nat_juridica (criado_por)');
        $this->addSql('CREATE INDEX IDX_3342F624AF2B1A92 ON ad_mod_nat_juridica (atualizado_por)');
        $this->addSql('CREATE INDEX IDX_3342F624A395BB94 ON ad_mod_nat_juridica (apagado_por)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3342F6242E892728FF6A170E ON ad_mod_nat_juridica (valor, apagado_em)');

        // Adicionando as constraints de chave estrangeira
        $this->addSql('ALTER TABLE ad_mod_nat_juridica ADD CONSTRAINT FK_3342F624F69C7D9B FOREIGN KEY (criado_por) REFERENCES ad_usuario (id)');
        $this->addSql('ALTER TABLE ad_mod_nat_juridica ADD CONSTRAINT FK_3342F624AF2B1A92 FOREIGN KEY (atualizado_por) REFERENCES ad_usuario (id)');
        $this->addSql('ALTER TABLE ad_mod_nat_juridica ADD CONSTRAINT FK_3342F624A395BB94 FOREIGN KEY (apagado_por) REFERENCES ad_usuario (id)');

        // Alterando a tabela ad_pessoa
        $this->addSql('ALTER TABLE ad_pessoa ADD mod_natur_juridica_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ad_pessoa ADD CONSTRAINT FK_8130BC77CD7106C6 FOREIGN KEY (mod_natur_juridica_id) REFERENCES ad_mod_nat_juridica (id)');
        $this->addSql('CREATE INDEX IDX_8130BC77CD7106C6 ON ad_pessoa (mod_natur_juridica_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ad_pessoa DROP CONSTRAINT FK_8130BC77CD7106C6');
        $this->addSql('ALTER TABLE ad_mod_nat_juridica DROP CONSTRAINT FK_3342F624F69C7D9B');
        $this->addSql('ALTER TABLE ad_mod_nat_juridica DROP CONSTRAINT FK_3342F624AF2B1A92');
        $this->addSql('ALTER TABLE ad_mod_nat_juridica DROP CONSTRAINT FK_3342F624A395BB94');
        $this->addSql('DROP TABLE ad_mod_nat_juridica');
        $this->addSql('DROP INDEX IDX_8130BC77CD7106C6');
        $this->addSql('ALTER TABLE ad_pessoa DROP COLUMN mod_natur_juridica_id');
    }
}
