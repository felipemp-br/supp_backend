<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250727180400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds tarefa_origem_id column to ad_tarefa table';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('ALTER TABLE ad_tarefa ADD tarefa_origem_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ad_tarefa ADD CONSTRAINT FK_D5C8A4F3443BBE78 FOREIGN KEY (tarefa_origem_id) REFERENCES ad_tarefa (id)');
        $this->addSql('CREATE INDEX IDX_D5C8A4F3443BBE78 ON ad_tarefa (tarefa_origem_id)');
    }

    public function down(Schema $schema): void
    {

        $this->addSql('ALTER TABLE ad_tarefa DROP FOREIGN KEY FK_D5C8A4F3443BBE78');
        $this->addSql('DROP INDEX IDX_D5C8A4F3443BBE78 ON ad_tarefa');
        $this->addSql('ALTER TABLE ad_tarefa DROP tarefa_origem_id');
    }
}