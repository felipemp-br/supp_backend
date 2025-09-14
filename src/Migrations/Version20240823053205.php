<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240823053205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration from 1.19.0 to 1.20.0';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ad_assinatura ADD padrao varchar(5) NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ad_assinatura DROP COLUMN padrao');
    }
}