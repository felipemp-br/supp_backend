<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506133614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adiciona o campo versao_editor na tabela ad_componente_digital';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE public.ad_componente_digital ADD COLUMN versao_editor character varying(255) DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN public.ad_componente_digital.versao_editor IS \'Versão do editor utilizado para criar ou modificar o componente digital\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE public.ad_componente_digital DROP COLUMN versao_editor');
    }
}
