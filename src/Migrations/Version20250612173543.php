<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration para criar o campo data_hora_disponibilizacao na tabela ad_tarefa!
 */
final class Version20250612173543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adiciona campo data_hora_disponibilizacao na tabela ad_tarefa';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ad_tarefa ADD COLUMN data_hora_disponibilizacao TIMESTAMP(0) DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN ad_tarefa.data_hora_disponibilizacao IS \'data_hora_disponibilizacao importada do jd_com_judicial\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ad_tarefa DROP COLUMN data_hora_disponibilizacao');
    }
}
