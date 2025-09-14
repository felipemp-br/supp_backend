<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration para adicionar campo distribuicao_estagiario_automatica na tabela ad_tarefa
 * Este campo controlará se a distribuição do estagiário foi feita automaticamente ou manualmente
 */
final class Version20250819235502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adiciona campo distribuicao_estagiario_automatica na tabela ad_tarefa para controlar distribuições automáticas de estagiários';
    }

    public function up(Schema $schema): void
    {
        // Adiciona o campo distribuicao_estagiario_automatica na tabela ad_tarefa
        $this->addSql('ALTER TABLE ad_tarefa ADD distribuicao_estagiario_automatica BOOLEAN DEFAULT FALSE NOT NULL');
        
        // Adiciona comentário explicativo no campo
        $this->addSql('COMMENT ON COLUMN ad_tarefa.distribuicao_estagiario_automatica IS \'Indica se a distribuição do estagiário foi feita automaticamente pelo sistema\'');
        
        // Cria índice para melhorar performance das queries que filtram por este campo
        $this->addSql('CREATE INDEX idx_tarefa_distribuicao_estagiario_auto ON ad_tarefa (distribuicao_estagiario_automatica)');
    }

    public function down(Schema $schema): void
    {
        // Remove o índice
        $this->addSql('DROP INDEX idx_tarefa_distribuicao_estagiario_auto');
        
        // Remove o campo
        $this->addSql('ALTER TABLE ad_tarefa DROP COLUMN distribuicao_estagiario_automatica');
    }
}