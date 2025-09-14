<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721141746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adiciona campo estagiario_responsavel_id na tabela ad_tarefa';

    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ad_tarefa ADD COLUMN estagiario_responsavel_id INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE ad_tarefa ADD CONSTRAINT fk_9ef45b4festagiario FOREIGN KEY (estagiario_responsavel_id) REFERENCES ad_usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_9ef45b4festagiario ON ad_tarefa USING btree (estagiario_responsavel_id ASC NULLS LAST)');
        $this->addSql('COMMENT ON COLUMN ad_tarefa.estagiario_responsavel_id IS \'ID do estagiário responsável pela tarefa\'');
    
    

    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ad_tarefa DROP CONSTRAINT IF EXISTS fk_9ef45b4festagiario');
        $this->addSql('DROP INDEX IF EXISTS idx_9ef45b4festagiario');
        $this->addSql('ALTER TABLE ad_tarefa DROP COLUMN estagiario_responsavel_id');


    }
}
